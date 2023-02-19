#! /bin/bash
docker build . -f backend/Dockerfile -t docker-backend 
docker build . -f frontend/Dockerfile -t docker-frontend
docker-compose up -d
echo "Application running on port 4200"
sleep 5
open http://localhost:4200