<?php
require_once __DIR__ . '/../config/db.php';

try {
    $hash = password_hash('Admin@1234', PASSWORD_ARGON2ID, [
        'memory_cost' => 65536,
        'time_cost' => 4,
        'threads' => 1
    ]);

    $stmt = $pdo->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute(['TaxSafar Admin', 'admin@taxsafar.com', $hash]);
    echo "Admin created successfully.\n";

    $inquiries = [
        ['Ravi Kumar', 'ravi@example.com', '+919876543210', 'Mumbai', 'GST Registration', 'Need GST fast', 'new'],
        ['Geeta Patel', 'geeta@example.com', '+919876543211', 'Delhi', 'Income Tax Return', 'File ITR', 'contacted'],
        ['Arun Vijay', 'arun@example.com', '+919876543212', 'Bangalore', 'Company Incorporation', 'Start pvt ltd', 'closed'],
        ['Meera Nair', 'meera@example.com', '+919876543213', 'Chennai', 'TDS Filing', 'Quarterly TDS', 'new'],
        ['Anil Desai', 'anil@example.com', '+919876543214', 'Pune', 'Accounting', 'Monthly bookkeeping', 'contacted'],
        ['Priya Menon', 'priya@example.com', '+919876543215', 'Hyderabad', 'Audit', 'Statutory audit', 'closed'],
        ['Deepak Raj', 'deepak@example.com', '+919876543216', 'Kolkata', 'ROC Compliance', 'Annual filing', 'new'],
        ['Kavita Rao', 'kavita@example.com', '+919876543217', 'Ahmedabad', 'Payroll', 'Payroll processing', 'contacted']
    ];

    $stmt = $pdo->prepare("INSERT INTO inquiries (full_name, email, mobile, city, service, message, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($inquiries as $inq) {
        $stmt->execute($inq);
        echo "Inquiry for {$inq[0]} created successfully.\n";
    }

    echo "\n*** NOTE: Delete this file after seeding for security! ***\n";

} catch (PDOException $e) {
    die("Seeding failed: " . $e->getMessage());
}
