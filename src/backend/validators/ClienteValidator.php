<?php

class ClienteValidator {
    public static function validarCampos($data){
        $erros = [];
        
        if (empty($data->nome)) {
            $erros[] = "O nome não pode ser vazio!";
        }
        if (empty($data->email)) {
            $erros[] = "Email não pode ser vazio!";
        }
        if (empty($data->telefone)) {
            $erros[] = "O telefone não pode ser vazio!";
        }
        if (empty($data->endereco)) {
            $erros[] = "O endereço não pode ser vazio!";
        }

        return $erros;
    }

    public static function validarEmail($email){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Email inválido!";
        }
        return null; 
    }

    public static function validarTelefone($telefone){
        if (!preg_match("/^\(\d{2}\) \d{4,5}-\d{4}$/", $telefone)) {
            return "Telefone inválido! Formato válido: (XX) XXXXX-XXXX";
        }
        return null; 
    }

    public static function verificarEmailExistente($email, $db){
        $sql = "SELECT * FROM clientes WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return "O email fornecido já foi registrado!";
        }

        return null;
    }

    public static function validar($data, $db){
        $erros = [];

        // Validar campos
        $erros = array_merge($erros, self::validarCampos($data));

        // Validar email
        $emailErro = self::validarEmail($data->email); 
        if ($emailErro) {
            $erros[] = $emailErro;
        }

        // Validar telefone
        $telefoneErro = self::validarTelefone($data->telefone);
        if ($telefoneErro) {
            $erros[] = $telefoneErro;
        }

        // Verificar se o email já existe
        $emailExistenteErro = self::verificarEmailExistente($data->email, $db);
        if ($emailExistenteErro) {
            $erros[] = $emailExistenteErro;
        }

        return empty($erros) ? true : $erros;
    }
}
