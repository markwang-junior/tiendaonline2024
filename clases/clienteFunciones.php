<!--funciones para validacion de los nuevos usuarios-->
<?php 

// Función para verificar si algún elemento en un array está vacío o nulo
function esNulo(array $parametros) 
{
    foreach ($parametros as $parametro) 
    {
        if (strlen(trim($parametro)) < 1) 
        {
            return true;
        }
    }
    return false;
}

// Función para validar si un string es una dirección de correo electrónico válida
function esEmail($email) 
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

// Función para validar si dos contraseñas coinciden
function validaPassword($password, $repassword) 
{
    if (strcmp($password, $repassword) === 0) {
        return true;
    }
    return false;
}


// Función para generar un token único
function generarToken()
{
    return md5(uniqid(mt_rand(), false));
}

// Función para registrar un nuevo cliente en la base de datos
function registraCliente(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO clientes (nombres, apellidos, email, telefono, dni, direccion, codigopostal, estatus, fecha_alta) VALUES(?,?,?,?,?,?,?,1,now())");
    if ($sql->execute($datos)) {
        return $con->lastInsertId();
    }
    return 0;
}

// Función para registrar un nuevo usuario en la base de datos
function registraUsuario(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO usuarios (usuario, password, token, id_cliente) VALUES (?,?,?,?)");
    if ($sql->execute($datos)) {
        return $con->lastInsertId();
    }
    return 0;
}

// Función para verificar si un usuario ya existe en la base de datos
function usuarioExiste($usuario, $con)
{
    $sql = $con->prepare("SELECT id FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    if ($sql->fetchColumn() > 0 ) {
        return true;
    }
    return false;
}

// Función para verificar si un correo electrónico ya existe en la base de datos
function emailExiste($email, $con)
{
    $sql = $con->prepare("SELECT id FROM clientes WHERE email LIKE ? LIMIT 1");
    $sql->execute([$email]);
    if ($sql->fetchColumn() > 0 ) {
        return true;
    }
    return false;
}

// Función para mostrar mensajes de error
function mostrarMensajes(array $errors) 
{
    if (count($errors) > 0) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach ($errors as $error) {
            echo '<li>' .  $error . '</li>';
        }
        echo '</ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}

// Función para validar el formato del DNI
function validarDNI($dni)
{
    // Expresión regular para verificar el formato del DNI
    $patron = '/^\d{8}[a-zA-Z]$/';

    // Verificar si el DNI coincide con el patrón
    if (preg_match($patron, $dni)) {
        return true;
    } else {
        return false;
    }
}

// Función para validar el número de teléfono
function validarTelefono($telefono)
{
    // Expresión regular para verificar el formato del número de teléfono
    $patron = '/^\d{9}$/';

    // Verificar si el número de teléfono coincide con el patrón
    if (preg_match($patron, $telefono)) {
        return true;
    } else {
        return false;
    }
}


// Función para validar un token de activación y activar la cuenta del usuario
function validaToken($id, $token, $con)
{
    $msg ="";
    $sql = $con->prepare("SELECT id FROM usuarios WHERE id = ? AND token LIKE ? LIMIT 1");
    $sql->execute([$id, $token]);
    if ($sql->fetchColumn() > 0 ) {
      if(activarUsuario($id, $con)) {
        $msg ="Cuenta activada con éxito.";
      } else {
        $msg ="Error al activar la cuenta.";
      }
    } else {
        $msg= " No existe el registro del cliente.";
    }
    return $msg;
}

// Función para activar un usuario en la base de datos
function activarUsuario($id, $con) {
    $sql = $con->prepare("UPDATE usuarios SET activacion = 1, token = '' WHERE id = ?");
    return $sql->execute([$id]);
}

// Función para realizar el proceso de inicio de sesión para usuarios administradores
function login($usuario, $password, $con, $proceso) {
        $sql = $con->prepare("SELECT id, usuario, password, id_cliente FROM usuarios WHERE usuario LIKE ? LIMIT 1");
        $sql->execute([$usuario]);
        if($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            if(esActivo($usuario,$con)) {
                if(password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_name'] = $row['usuario'];
                    $_SESSION['user_cliente'] = $row['id_cliente'];
                    if($proceso == 'pago'){
                        header("location: checkout.php");
                    } else {
                        header("location: index.php");
                    }
                    exit;
                }
            } else {
                return 'El usuario no está activo.';
            }
        }
        return 'El usuario y/o contraseña son incorrectos.';
    }

// Función para verificar si un usuario está activo
function esActivo($usuario,$con) {
    $sql = $con->prepare("SELECT activacion FROM usuarios WHERE usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if($row['activacion'] == 1) {
        return true;
    }
    return false;
}

// Función para solicitar un cambio de contraseña y generar un token para ello
function solicitaPassword($user_id, $con) {
    $token = generarToken();
    $sql = $con->prepare("UPDATE usuarios SET token_password = ?, password_request= 1 WHERE id = ?");
    if($sql->execute([$token, $user_id])) {
        return $token;
    }
    return null;
}

// Función para verificar si un token de solicitud de cambio de contraseña es válido
function verificaTokenRequest($user_id, $token, $con) {
        $sql = $con->prepare("SELECT id FROM usuarios WHERE id = ? AND token_password LIKE ? AND password_request=1 LIMIT 1");
        $sql->execute([$user_id, $token]);
        if($sql->fetchColumn() > 0) {
            return true;
        }
        return false;
}

// Función para actualizar la contraseña de un usuario
function actualizaPassword($user_id, $password, $con) 
{
    $sql= $con->prepare("UPDATE usuarios SET password = ?, token_password = '', password_request = 0 WHERE id = ?");
    if($sql->execute([$password, $user_id])) {
        return true;
        }
        return false;
    }

    // Perfil.php
    // Función para comprobar si la contraseña actual del cliente coincide con la almacenada en la base de datos
function comprobarContraseñaActual($usuario, $contraseña, $con)
{
    $sql = $con->prepare("SELECT password FROM usuarios WHERE usuario = ? LIMIT 1");
    $sql->execute([$usuario]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    // Verificar si la contraseña actual coincide con la almacenada en la base de datos
    if ($row && password_verify($contraseña, $row['password'])) {
        return true;
    } else {
        return false;
    }
}

// Función para verificar si las contraseñas coinciden y actualizar la contraseña en la base de datos
function actualizarContraseña($usuario, $contraseña, $con)
{
    $hashed_password = password_hash($contraseña, PASSWORD_DEFAULT);
    $sql = $con->prepare("UPDATE usuarios SET password = ? WHERE usuario = ?");
    if ($sql->execute([$hashed_password, $usuario])) {
        // Aquí estableces el mensaje de éxito
        $_SESSION['exito'] = "La contraseña se ha cambiado con éxito.";
        return true;
    } else {
        return false;
    }
}


?>


