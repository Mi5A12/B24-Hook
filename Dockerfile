# Use official PHP image
FROM php:8.1-cli

# Set working directory
WORKDIR /var/www/html

# Copy app files
COPY . .

# Expose port (Render uses PORT env variable, default to 8080)
ENV PORT=8080
EXPOSE 8080

# Start a PHP built-in server
CMD ["php", "-S", "0.0.0.0:8080"]
