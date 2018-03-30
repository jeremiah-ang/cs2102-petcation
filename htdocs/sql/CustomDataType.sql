-- 2.1, 2.2, 2.3
DROP TYPE IF EXISTS status, petsize, pettype, transtype, usertype CASCADE;

CREATE TYPE status as ENUM ('ACTIVE', 'INACTIVE');
CREATE TYPE transtype as ENUM ('CREDIT', 'DEBIT');