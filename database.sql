-- PostgreSQL Schema for TaxSafar

DROP TABLE IF EXISTS inquiries CASCADE;
DROP TABLE IF EXISTS admins CASCADE;

CREATE TABLE admins (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE inquiries (
    id SERIAL PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    mobile VARCHAR(20) NOT NULL,
    city VARCHAR(100) NOT NULL,
    service VARCHAR(100) NOT NULL,
    message TEXT,
    status VARCHAR(20) DEFAULT 'new' CHECK (status IN ('new', 'contacted', 'closed')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_inquiries_email ON inquiries(email);
CREATE INDEX idx_inquiries_mobile ON inquiries(mobile);
CREATE INDEX idx_inquiries_status ON inquiries(status);
CREATE INDEX idx_inquiries_full_name ON inquiries(full_name);

INSERT INTO admins (name, email, password) VALUES 
('TaxSafar Admin', 'admin@taxsafar.com', '$argon2id$v=19$m=65536,t=4,p=1$Sk1OL05BVE5DTFppOER0Rw$RHio4unsJcksPdYzH388wVxUxm9nLiIPM4YBGabiGDY');

INSERT INTO inquiries (full_name, email, mobile, city, service, message, status) VALUES 
('Rahul Sharma', 'rahul@example.com', '9876543210', 'Mumbai', 'GST Registration', 'Need help with new GST registration', 'new'),
('Priya Singh', 'priya@example.com', '9876543211', 'Delhi', 'Income Tax Filing', 'Urgent ITR filing required', 'contacted'),
('Amit Patel', 'amit@example.com', '9876543212', 'Ahmedabad', 'Company Incorporation', 'Want to register a Pvt Ltd company', 'new'),
('Neha Gupta', 'neha@example.com', '9876543213', 'Bangalore', 'TDS Return Filing', 'Quarterly TDS return filing', 'closed'),
('Vikram Joshi', 'vikram@example.com', '9876543214', 'Pune', 'Accounting Services', 'Looking for monthly accounting services', 'contacted');
