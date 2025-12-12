#!/bin/bash
#SAMPLE OWNER ACCOUNT ONLY
# This script creates a sample owner account in the database
# Password: password (hashed with bcrypt)

docker exec -it hitchhikers_db mysql -u root -p <<EOF

USE hitchhikers;

-- Insert owner account
-- Password is 'password' hashed with bcrypt
INSERT INTO users (
    first_name,
    middle_name,
    last_name,
    ext_name,
    gender,
    email,
    phone,
    birthdate,
    user_type,
    password,
    account_status,
    email_verified_at,
    created_at,
    updated_at
) VALUES (
    'Owner',
    'Sample',
    'Account',
    NULL,
    'male',
    'owner@hitchhike.com',
    '+639123456789',
    '1990-01-01',
    'owner',
    '\$2y\$12\$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5NANClx6W.7G2',
    'active',
    NOW(),
    NOW(),
    NOW()
);

-- Show the created account
SELECT id, first_name, last_name, email, user_type, account_status 
FROM users 
WHERE email = 'owner@hitchhike.com';

EOF

echo ""
echo "Owner account created successfully!"
echo "Email: owner@hitchhike.com"
echo "Password: password"
echo ""