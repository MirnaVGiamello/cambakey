@echo off
chcp 65001 > nul
echo  Iniciando Cambakey...
docker-compose up -d
echo.
echo  App:        http://localhost:8090
echo  phpMyAdmin: http://localhost:8091
echo.
