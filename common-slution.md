## Solutions 

in manifest not found in pord

```
# from project root on the HOST
docker run --rm -v "$PWD":/app -w /app node:20-alpine sh -lc "
  npm ci && npm run build
"
# then restart
docker compose -f docker-compose.prod.yml up -d

```