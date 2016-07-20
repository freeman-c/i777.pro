<?php

// http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers

/**
 * Allow access only from Binotel server
 */
if ($_SERVER['REMOTE_ADDR'] !== '194.88.218.114' && $_SERVER['REMOTE_ADDR'] !== '194.88.218.116' && $_SERVER['REMOTE_ADDR'] !== '194.88.218.117') {
    die(sprintf('Access denied!%s', PHP_EOL));
}


$phoneNumber = (isset($_REQUEST['srcNumber']) ? trim($_REQUEST['srcNumber']) : '');

if (empty($phoneNumber)) {
    die(sprintf('Phone number is empty!%s', PHP_EOL));
}


/**
 * Open conntect to database
 */
// 
try {
    $db = new PDO(sprintf('mysql:host=%s;dbname=%s;charset=utf8', 'database-host', 'database-name'), 'username', 'password');
} catch (PDOException $e) {
    die(sprintf('Error: %s %s', $e->getMessage(), PHP_EOL));
}


try {
    $stmt = $db->prepare('
                            SELECT
                                `id`,
                                `first_name`,
                                `assigned_to_id`
                            FROM
                                `contacts`
                            WHERE
                                `phone` LIKE ?
                            LIMIT 0, 1
                         ');

    $stmt->execute(array('%'. $phoneNumber .'%'));
    $contactData = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die(sprintf('Error: %s %s', $e->getMessage(), PHP_EOL));
}

if ($contactData) {
    try {
        $stmt = $db->prepare('
                                SELECT
                                    `mail`
                                FROM
                                    `users`
                                WHERE
                                    `id` = ?
                                LIMIT 0, 1
                             ');

        $stmt->execute(array($contactData['assigned_to_id']));
        $assignedManagerEmail = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die(sprintf('Error: %s %s', $e->getMessage(), PHP_EOL));
    }

    die(json_encode(array(
        'customerData' => array(
            'name' => $contactData['first_name'],
            'assignedToEmployeeEmail' => ($assignedManagerEmail ? $assignedManagerEmail['mail'] : ''),
            'linkToCrmUrl' => sprintf('https://crm.crm.crm/contacts/%s/', $contactData['id'])
        )
    )));
}