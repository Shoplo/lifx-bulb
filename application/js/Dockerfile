FROM node:latest

WORKDIR /application

COPY package.json .

RUN npm install

COPY . .

EXPOSE 8080
CMD [ "npm", "start" ]