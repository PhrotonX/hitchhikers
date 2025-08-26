# Workarounds for issues within WSL2 or Windows Subsystem for Linux
- Use the following commands on the root directory of Hitchhikers if EACCESS errors arise. These errors arise everytime a php artisan command generates a file. It only affects the generated file.
```sudo chown -R $USER:$USER .```