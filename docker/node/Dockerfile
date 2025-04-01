FROM node:20-alpine

WORKDIR /app

RUN npm install -g npm@latest

RUN mkdir -p /home/node/.npm-global && \
    chown -R node:node /home/node && \
    npm config set prefix '/home/node/.npm-global'

ENV PATH=/home/node/.npm-global/bin:$PATH

USER node

EXPOSE 5173