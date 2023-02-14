Aplicação para leitura e formatação de um arquivo excel.
Origem: revista do INPI em XML

Instalação:

1 - Instalar o XAMPP, versão com PHP 8
https://www.apachefriends.org/download.html

2 - Importar a base de dados do repositório no MySQL do xampp.

3 - Apagar todo o conteúdo da pasta do xampp / htdocs e colocar o conteúdo da pasta public_html do repositório.

4 - No arquivo em pasta do xampp/apache/conf/extrahttpd-vhosts.conf adicionar, no final:

<VirtualHost *:80>
    DocumentRoot "F:/xampp8/htdocs"
    ServerName site.leitorINPI
</VirtualHost>
<VirtualHost *:443>
    DocumentRoot "F:/xampp8/htdocs"
    ServerName site.leitorINPI
	SSLEngine on
	SSLCertificateFile "conf/ssl.crt/server.crt"
    SSLCertificateKeyFile "conf/ssl.key/server.key"
</VirtualHost>

5 - No arquivo C:\Windows\System32\drivers\etc\hosts adicionar ao final (precisa permissão de administrador):

127.0.0.1   site.leitorINPI