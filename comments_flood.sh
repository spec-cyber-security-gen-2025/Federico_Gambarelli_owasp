#!/bin/bash

# URL dell'endpoint di commento
URL="http://127.0.0.1:8000/articles/2/comments"

# Max requests to sent
NUM_REQUESTS=10

# Get csrf token somehow
CSRF_TOKEN="nVvOQZ3bWjFBXWUxXECsstscRxtquZ4J3WfwIZzy"

# Retrieve the session coockie somehow (csrf attack)
SESSION_COOKIE="laravel_session=eyJpdiI6ImxuTWY3M29vSkZsdmswUjEzbFNMYmc9PSIsInZhbHVlIjoiVytIRkJlZGNCV3pRQWhzRTIrRTRuMkNlVkZJN3laNkJPcjRraGFjVVdSVkhtVDZ0WGFaMUVkREdMYVpQNkZFU1JCSmg3dFB0V2c3dDhnbW5JakVGTHUwc1QxVzk1dnRzOXk1TzJ3UHd0S25MY2JTaU4rUlBzVGhjME9qZGdVTWQiLCJtYWMiOiJiNTM4OWE2NjBiOTY3NDk4Nzk0MTFjODRmNDJhZDVkNDBhNzQ0ZTA0ODUxMWFjMTUzOTEyNGIwYTdjZjJjMjYyIiwidGFnIjoiIn0%3D"

# Send a comment including csrf token
send_comment() {
    local comment_number=$1
    curl -s -H "Cookie: $SESSION_COOKIE" -X POST -d "content=Questo è un commento di prova $1&_token=$CSRF_TOKEN" "$URL"
}

# Sending multiple comments
for ((i = 1; i <= NUM_REQUESTS; i++))
do
    send_comment $i
    echo "Comment $i sent"
done
