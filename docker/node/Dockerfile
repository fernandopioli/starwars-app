FROM node:20-alpine

WORKDIR /app/app

RUN npm install -g npm@latest

# Copy entrypoint script DIRECTLY into the container
COPY docker/node/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set permissions
RUN mkdir -p /home/node/.npm-global && \
    chown -R node:node /home/node && \
    npm config set prefix '/home/node/.npm-global'

ENV PATH=/home/node/.npm-global/bin:$PATH

USER node

EXPOSE 5173