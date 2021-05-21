mercure_server_v1:
	JWT_KEY='9E87256C84634E3FA4634F1132D57' ADDR='localhost:3000' ALLOW_ANONYMOUS=1 CORS_ALLOWED_ORIGINS=http://localhost:8000 ./mercurev1/mercure
mercure_server_v2:
	./mercure/mercure run --config ./mercure/Caddyfile