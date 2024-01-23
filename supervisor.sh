#!/bin/sh

service supervisor start

# Add a delay of 5 seconds
sleep 5

# Check if the Supervisor service is running
if supervisorctl -c /etc/supervisor/supervisord.conf status | grep -q "RUNNING"; then
    echo "Supervisor service is already running. Skipping..."
else
    # Start the Supervisor service
    supervisorctl -c /etc/supervisor/supervisord.conf reread
    supervisorctl -c /etc/supervisor/supervisord.conf update
    supervisorctl -c /etc/supervisor/supervisord.conf clear talent-one:*
    supervisorctl -c /etc/supervisor/supervisord.conf start talent-one:*
fi

# Run the CMD from the Dockerfile
exec "$@"