<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PrototypeController extends Controller
{
     /**
     * Llave base
     *
     * @var string
     */
    private $key = null;
    
    /**
     * string
     *
     * @var string
     */
    private $StringEncrypted = null;
    
    /**
     * string
     *
     * @var string
     */
    private $StringDecrypted = null;
   
    /**
     * string
     *
     * @var string
     */
    static protected $Config_SSL = [
        "private_key_bits" => 1024,
        "config" => "C:/xampp/php/extras/openssl/openssl.cnf",
        "private_key_type" => OPENSSL_KEYTYPE_RSA
    ];
      
    /**
     * string
     *
     * @var string
     */
    protected  $ResponseKey;
      

    /**
     * Show the form
     *
     * @param  void
     * @return \Illuminate\View\View
     */
    final private function generateNewKey($method = null)
    {
        $this->ResponseKey = @openssl_pkey_new(self::$Config_SSL);
        @openssl_pkey_export($this->ResponseKey, $privateKey,NULL,self::$Config_SSL);
        return $object_keys = [
            "private" => $privateKey,
            "public"  => @openssl_pkey_get_details($this->ResponseKey)
        ];
    }

    /**
     * Show the form
     *
     * @param  void
     * @return \Illuminate\View\View
     */
    final private function encryptString($stringToEncrypt, $keyReqPriv="")
    {
        //Se valida si las llaves concuerdan
        if($this->validateKeys($keyReqPriv)){
            $this->StringEncrypted = @openssl_public_encrypt($stringToEncrypt,$encryptedString, session('publicKey'));
        }
        return base64_encode($encryptedString);
    }
 
    /**
     * Show the form
     *
     * @param  void
     * @return \Illuminate\View\View
     */
    final  private function decryptString($encryptedString = "", $keyReqPriv="")
    {
        //Desencripta
        if($this->validateKeys($keyReqPriv)){
        $this->StringDecrypted = @openssl_private_decrypt(base64_decode($encryptedString),$decryptedString, session('privateKey'));
        }

        return $decryptedString;
    }
   
    /**
     * Show the form
     *
     * @param  void
     * @return \Illuminate\View\View
     */
     public function index()
     {
         return view('prototype.index');
     }

    /**
     * Show the form
     *
     * @param  void
     * @return \Illuminate\View\View
     */
    public function generateKey(Request $request)
    {
        //1.- Validar existen los parametros en el request
        //2.- Mostrar mensajes flash de los errores
        // Tareas el metodo y generar una LLAVE NUEVA
        //Validar

        ##Se limpian las variables de sesión
        session()->forget('publicKey');
        session()->forget('privateKey');
        if($request->has("method")){
            try{
                $objectKeys = $this->generateNewKey();
                $publicKey  = $objectKeys['public']['key'];
                $privateKey = $objectKeys['private']; 
                //Variables globales
                session(["publicKey"=>$publicKey]);
                session(["privateKey" => $privateKey]);
                return response()->json([
                    "error" => false,
                    "data"  => $publicKey,
                    "msj" => 'Llave generada'
                ]);
            }catch(\Exception $error){
                return response()->json([
                    "error" => true,
                    "data"  => null,
                    "msj" => 'Error al generar llave' . $error
                ]);            
            }
        }else{
            return response()->json(["error" => true]);
        }
    }

    
    /**
     * Show the form
     *
     * @param  Request
     * @return \Illuminate\View\View
     */
    public function encrypt(Request $request)
    {
        //1.- Validar existen los parametros en el request
        //2.- Mostrar mensajes flash de los errores

        // Tareas recibir una llave y una cadena 
        // La llave tendrá que ser la misma que la seteada como propiedad
        // La cadena puede ser encriptada con OPEN SSL solamente con AES de 128bits
        // Se tiene que generar otro formulario(view) para DESENCRIPTAR una cadena privamente encriptada
        //
        //validar
        if($request->has('key') && $request->has('encrypt_string')){
            try{
                $encryptedString = $this->encryptString($request->encrypt_string,(string)$request->key);
                return response()->json([
                    "error" => false,
                    "data"  => $encryptedString,
                    "msj" => 'La cadena se encripto correctamente'
                ]);
            }catch(\Exception $error){
                return response()->json([
                    "error" => true,
                    "msj" => 'Ocurrio un error al encriptar'
                ]);
            }
        }else{
            return response()->json(["error" => true]);
        }
    }
    
    /**
     * Show the form
     *
     * @param  Request
     * @return \Illuminate\View\View
     */
    public function decrypt(Request $request)
    {
        //1.- Validar existen los parametros en el request
        //2.- Mostrar mensajes flash de los errores

        // Tareas recibir una llave y una cadena 
        // La llave tendrá que ser la misma que la seteada como propiedad
        // La cadena puede ser encriptada con OPEN SSL solamente con AES de 128bits
        // Se tiene que generar otro formulario(view) para DESENCRIPTAR una cadena privamente encriptada
        //

        //validar
        if($request->has('key') && $request->has('decrypt_string')){
            try{
                $decryptedString = $this->decryptString($request->decrypt_string,(string)$request->key);
                return response()->json([
                    "error" => false,
                    "data"  => $decryptedString,
                    "msj" => 'La cadena se desencripto correctamente'
                ]);
            }catch(\Exception $error){
                //Control de mensajes
                return response()->json([
                    "error" => true,
                    "msj" => 'Ocurrio un error al desencriptar'
                ]);
            }
        }else{
            return response()->json(["error" => true]);
        }
    }

    private function validateKeys($inputKey =""){

        //Se limpian los espacios en blanco y saltos de linea
        $generatedKey = trim(str_replace(' ', '', (string)session('publicKey')));
        $cleanKey     = preg_replace("/[\r\n|\n|\r]+/","",$generatedKey);
        $inputKey     = trim(str_replace(' ', '', $inputKey));
        $cleanKeyReq  = preg_replace("/[\r\n|\n|\r]+/","",$inputKey);

        $equalsKeys = ($cleanKey == $cleanKeyReq)?true: false;

        return $equalsKeys;
    }
}