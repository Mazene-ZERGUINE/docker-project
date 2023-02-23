FROM node:latest as build

WORKDIR /usr/local/app

COPY ./frontend /usr/local/app/

RUN npm ci
RUN npm run build


FROM nginx:latest

# Copy the build output to replace the default nginx contents.
COPY --from=build /usr/local/app/dist/frontend /usr/share/nginx/html

# Expose port 80
EXPOSE 80
