//server.js
import express from "express";
import rateLimit from "express-rate-limit";
import helmet from "helmet";
import cors from "cors";
import dotenv from "dotenv";

dotenv.config();
const app = express();

// Helmet - CSP & HSTS (tune for your frontend in ALLOWED_ORIGINS)
app.use(
  helmet({
    contentSecurityPolicy: {
      directives: {
        defaultSrc: ["'self'"],
        scriptSrc: ["'self'"],
        connectSrc: ["'self'"],
        imgSrc: ["'self'"],
        styleSrc: ["'self'"],
        frameAncestors: ["'none'"]
      }
    },
    hsts: {
      maxAge: 31536000,
      includeSubDomains: true,
      preload: true
    }
  })
);

// CORS
const allowedOrigins = (process.env.ALLOWED_ORIGINS || "http://localhost:3000").split(",");
app.use(
  cors({
    origin: function (origin, callback) {
      if (!origin) return callback(null, true);
      if (allowedOrigins.includes(origin)) return callback(null, true);
      return callback(new Error("CORS policy: origin not allowed"), false);
    },
    methods: ["GET","POST","PUT","DELETE"],
    credentials: true
  })
);

// Rate limiters
const apiLimiter = rateLimit({
  windowMs: 15*60*1000,
  max: 100,
  standardHeaders: true,
  legacyHeaders: false,
  message: { error: "Too many requests, try again later." }
});
app.use("/api/", apiLimiter);

const loginLimiter = rateLimit({
  windowMs: 15*60*1000,
  max: 5,
  message: { error: "Too many login attempts. Try again later." }
});

// API key middleware
const validApiKey = process.env.API_KEY || "";
function requireApiKey(req, res, next) {
  const key = req.header("x-api-key") || req.query.api_key;
  if (!key || key !== validApiKey) {
    return res.status(401).json({ error: "Unauthorized" });
  }
  next();
}

// Routes
app.get("/", (req, res) => res.send("Hello Secure API 🚀"));

app.post("/auth/login", loginLimiter, (req, res) => {
  // placeholder login
  res.json({ ok: true, message: "Login endpoint (rate limited)" });
});

app.get("/api/secure", requireApiKey, (req, res) => {
  res.json({ secret: "Sensitive data only for authorized clients" });
});

const port = process.env.PORT || 3000;
app.listen(port, () => console.log(`🚀 Server running on port ${port}`));
