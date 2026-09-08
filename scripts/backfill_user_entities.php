<?php

$dsn = 'mysql:host=127.0.0.1;dbname=portal_local';
$user = 'root';
$pass = '';

$pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$map = [
    '@islagaia.pt' => 'ISLA GAIA',
    '@esht.ipp.pt' => 'ESHT.IPP',
    '@iscap.pt' => 'ISCAP.PP',
    '@iscap.ipp.pt' => 'ISCAP.PP',
    '@iscac.pt' => 'ISCAC',
    '@ipca.pt' => 'IPCA',
    '@ispgaya.pt' => 'ISPGAYA',
    '@esec.pt' => 'ESEC',
    '@ua.pt' => 'UA',
    '@ceos.pp' => 'CEOS.PP',
];

$stmt = $pdo->prepare(
    'update users set entidade=?, entities=JSON_ARRAY(?) where lower(email) like ?'
);

foreach ($map as $domain => $entity) {
    $like = '%' . strtolower($domain);
    $stmt->execute([$entity, $entity, $like]);
}

$pdo->exec(
    "update users set entidade='OTHER', entities=JSON_ARRAY('OTHER') " .
    "where (entidade is null or entidade='') " .
    "and (entities is null or ifnull(json_length(entities),0)=0)"
);

echo "done";
