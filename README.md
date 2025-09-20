 Week 4: Advanced Threat Detection & Web Security

1:Goal
Implement advanced security measures, detect threats in real-time, and secure API endpoints.

2:Features
- Intrusion Detection with Fail2Ban (SSH + Nginx)
- Rate-limiting using express-rate-limit
- CORS restricted to trusted origins
- API-key authentication
- Security headers (Helmet: CSP + HSTS)
- Fail2Ban infra samples and README

 3:Quick Start (Local)
1. Clone or upload this repo to your Linux machine.
2. Copy `.env.example` → `.env` and set API_KEY (or use the generated .env included if you prefer).
3. Install dependencies:
   ```bash
   npm install
Start server:

npm start


4:Test:

Public: curl http://localhost:3000

Protected: curl -H "x-api-key: <API_KEY>" http://localhost:3000/api/secure

Fail2Ban Setup (Linux)

5:Install fail2ban:

sudo apt update
sudo apt install fail2ban -y


6:Copy config files:

infra/fail2ban/jail.local → /etc/fail2ban/jail.local

infra/fail2ban/nginx-auth.conf → /etc/fail2ban/filter.d/nginx-auth.conf

7:Restart fail2ban:

sudo systemctl enable --now fail2ban
sudo systemctl restart fail2ban


8:Verify:

sudo fail2ban-client status
sudo fail2ban-client status sshd

9:Files included

server.js — secure Express API with Helmet, CSP, HSTS, CORS, rate-limiting, API-key middleware

package.json — dependencies & start script

.env.example — sample env file

infra/fail2ban/jail.local — example fail2ban jail.local

infra/fail2ban/nginx-auth.conf — fail2ban filter for nginx auth errors

10:Security notes

Never commit .env to git (this repo includes .env.example only).

For production: use HTTPS (Nginx + Certbot), secrets manager, Redis-backed rate limiter for multi-instance apps, and OAuth/JWT for user auth.

11:Project Structure:
week4-security/
│── server.js                # Express server
│── package.json             # Node.js dependencies
│── .env.example             # Example environment variables
│── .gitignore
│── infra/
│   └── fail2ban/
│       ├── jail.local
│       └── nginx-auth.conf
