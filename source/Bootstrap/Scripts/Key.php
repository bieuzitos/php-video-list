<?php

/*
|--------------------------------------------------------------------------
| Key
|--------------------------------------------------------------------------
|
| Verifica a existência da chave 'APP_KEY' no arquivo .env e a atualiza
| com uma nova chave aleatória, se necessário.
|
*/

$envFilePath = dirname(__DIR__, 3) . '\.env';

if (file_exists($envFilePath)) {
    $envContent = file_get_contents($envFilePath);

    if (strpos($envContent, 'APP_KEY=') !== false) {
        $envContent = preg_replace('/^APP_KEY=.*/m', 'APP_KEY="' . base64_encode(random_bytes(32)) . '"', $envContent);
        file_put_contents($envFilePath, $envContent);

        echo "\n**Importante:** Atualizar APP_KEY com um banco de dados já em produção, pode resultar em perda de dados.\n";
        echo "\nA chave APP_KEY foi atualizada com sucesso.\n";
        return;
    }

    echo "\nA variável APP_KEY não está configurada no arquivo .env.\n";
    return;
}

echo "\nO arquivo .env não foi encontrado.\n";
