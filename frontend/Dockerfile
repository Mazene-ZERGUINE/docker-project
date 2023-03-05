FROM node:latest

RUN mkdir /app
WORKDIR /app

RUN npm install -g @angular/cli
COPY ./frontend/package*.json ./
RUN npm ci

COPY ./frontend .
CMD ["ng", "serve", "--host", "0.0.0.0"]
