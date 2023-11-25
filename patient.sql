use aztecusers;
CREATE TABLE patients (
    patientid INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    dateofbirth DATE NOT NULL,
    address VARCHAR(255),
    city VARCHAR(50),
    state VARCHAR(2),
    zipcode VARCHAR(10),
    email VARCHAR(255)
);

CREATE TABLE adhddata (
    recordid INT AUTO_INCREMENT PRIMARY KEY,
    patientid INT,
    referralsource VARCHAR(50),
    previousdiagnosis VARCHAR(50),
    previoustesting VARCHAR(3),  -- Assuming Yes/No values
    testingneeded VARCHAR(3),
    testingperformed VARCHAR(3),
    finaldiagnosis VARCHAR(50),
    FOREIGN KEY (patientid) REFERENCES patients(patientid)
);
CREATE TABLE impact (
    recordid INT AUTO_INCREMENT PRIMARY KEY,
    patientid INT,
    testtype VARCHAR(50),
    testdate DATE,
    testversion VARCHAR(50),
    verbalmemorycomposite FLOAT,
    visualmemorycomposite FLOAT,
    visualmotorcomposite FLOAT,
    reactiontimecomposite FLOAT,
    headache VARCHAR(10),
    nausea VARCHAR(10),
    FOREIGN KEY (patientid) REFERENCES patients(patientid)
);
CREATE TABLE sportsdata (
    recordid INT AUTO_INCREMENT PRIMARY KEY,
    patientid INT,
    sport VARCHAR(50),
    skynumbase INT,
    datebase DATE,
    skynumfollowup1 INT,
    datefollowup1 DATE,
    skynumfollowup2 INT,
    datefollowup2 DATE,
    FOREIGN KEY (patientid) REFERENCES patients(patientid)
);
CREATE TABLE injurydata (
    recordid INT AUTO_INCREMENT PRIMARY KEY,
    patientid INT,
    type VARCHAR(50),
    testdate DATE,
    incidentdate DATE,
    asi_1 INT,
    asi_2 INT,
    asi_3 INT,
    asi_4 INT,
    asi_5 INT,
    asi_6 INT,
    asi_7 INT,
    FOREIGN KEY (patientid) REFERENCES patients(patientid)
);
CREATE TABLE patientdemographics (
    patientid INT AUTO_INCREMENT PRIMARY KEY,
    gender CHAR(1) NOT NULL,
    age INT,
    handedness VARCHAR(20),
    height FLOAT,
    weight FLOAT,
    educationlevel VARCHAR(50),
	FOREIGN KEY (patientid) REFERENCES patients(patientid)
);