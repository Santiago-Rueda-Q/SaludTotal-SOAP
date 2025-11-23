# Guía de instalación

1. Copie la carpeta `SaludTotal-SOAP` en su servidor local (por ejemplo `C:\laragon\www\SaludTotal-SOAP`).
2. Verifique que PHP tenga habilitada la extensión `soap`.
3. Revise que la ruta configurada en `public/wsdl/pacientes.wsdl` (`soap:address location`) apunte a la URL donde expone el servidor, por ejemplo:
   - `http://localhost/SaludTotal-SOAP/public/server/index.php`
4. Asegúrese de que la carpeta `storage/` tenga permisos de escritura.
5. Ingrese en el navegador a:
   - `http://localhost/SaludTotal-SOAP/public/index.php`
