FROM php:8.1-cli

# Set working directory
WORKDIR /var/www/html

# Copy all files
COPY . .

# Expose the port used by the app
ENV PORT=8080
EXPOSE 8080

# Start the PHP built-in server and serve current dir
CMD ["php", "-S", "0.0.0.0:8080", "-t", "/var/www/html"]
