<h3>Una de las pruebas que he presentado y me han gustado. Encriptación AES</h3>
<b>Realizado en Laravel </b></br>

<h4>Explicación del proyecto: </h4>

<ul>
    <li>1. Generar la llave para encriptar y desencriptar los mensajes </li>
    <li>2. Para encriptar: Se debe ingresar mensaje junto con la llave previamente creada, eso generará la cadena encriptada</li>
    <li>3. Para desencriptar: Ingresar la cadena encriptada y la llave que se creó. </li>
</ul>
Todo gira entorno a la llave pública, internamente se manipula la llave privada. </br></br>

<br>
<b>Configuración adicional en el proyecto</b></br>
Si el cifrado de OpenSSL presenta problemas he incluido un .rar llamado "extras" que ya cuenta con el archivo openssl.cnf, es el que hace referencia a la configuración del encriptado: <br>
![image](https://user-images.githubusercontent.com/33740828/123029836-5c82b080-d3a7-11eb-89e6-0635d06ad4e3.png)

<br><br>

La clave que se genera desde el principio es la mismma que se debe usar para encriptar y desencriptar <br>
![image](https://user-images.githubusercontent.com/33740828/123029756-3bba5b00-d3a7-11eb-9565-325ace67dc3d.png)

Para encriptar hay que ingresar la llave previamente generada y el texto que se quiera encriptar: <br>
![image](https://user-images.githubusercontent.com/33740828/123029801-4f65c180-d3a7-11eb-8ace-6d7f4c68efd7.png)<br>Una vez presionado el botón, generará en el alert la encriptación.
<br><br><br>

Para desencriptar se necesita ingresar la llave que se genero desde la primera instancia, se debe poner la cadena encriptada que se genero previamente y clic sobre el botón desencriptar:<br>
![image](https://user-images.githubusercontent.com/33740828/123029851-63a9be80-d3a7-11eb-9268-e1fe73424de2.png)
<br><br><br>
Saludos y un abrazo.
