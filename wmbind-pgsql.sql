--
-- PostgreSQL database dump
--

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

CREATE TYPE admin AS ENUM (
    'no',
    'yes'
);

CREATE TYPE preftype AS ENUM (
    'record',
    'normal'
);

SET default_tablespace = '';
SET default_with_oids = false;

CREATE TABLE options (
    prefkey character varying NOT NULL,
    preftype preftype NOT NULL,
    prefval character varying
);

CREATE TYPE valid AS ENUM (
    'unknown',
    'yes',
    'no'
);

CREATE TABLE records (
    id integer NOT NULL,
    zone integer DEFAULT 0 NOT NULL,
    host character varying(255) NOT NULL,
    type character varying(10) NOT NULL,
    pri integer DEFAULT 0 NOT NULL,
    destination character varying(255) NOT NULL,
    valid valid DEFAULT 'unknown'::valid NOT NULL
);


CREATE TYPE updated AS ENUM (
    'yes',
    'no'
);

CREATE TABLE users (
    id integer NOT NULL,
    username character varying(255) NOT NULL,
    password character varying(40) NOT NULL,
    admin admin DEFAULT 'no'::admin NOT NULL
);


CREATE TABLE zones (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    pri_dns character varying(100) DEFAULT NULL::character varying,
    sec_dns character varying(100) DEFAULT NULL::character varying,
    serial integer DEFAULT 0 NOT NULL,
    refresh integer DEFAULT 604800 NOT NULL,
    retry integer DEFAULT 86400 NOT NULL,
    expire integer DEFAULT 2419200 NOT NULL,
    ttl integer DEFAULT 604800 NOT NULL,
    transfer character varying,
    valid valid DEFAULT 'unknown'::valid NOT NULL,
    owner integer DEFAULT 1 NOT NULL,
    updated updated DEFAULT 'yes'::updated NOT NULL
);


CREATE SEQUENCE records_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER SEQUENCE records_id_seq OWNED BY records.id;

SELECT pg_catalog.setval('records_id_seq', 1, true);

CREATE SEQUENCE users_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;

ALTER SEQUENCE users_id_seq OWNED BY users.id;


SELECT pg_catalog.setval('users_id_seq', 1, true);


CREATE SEQUENCE zones_id_seq
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER SEQUENCE zones_id_seq OWNED BY zones.id;

SELECT pg_catalog.setval('zones_id_seq', 1, true);

ALTER TABLE records ALTER COLUMN id SET DEFAULT nextval('records_id_seq'::regclass);
ALTER TABLE users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);
ALTER TABLE zones ALTER COLUMN id SET DEFAULT nextval('zones_id_seq'::regclass);

COPY options (prefkey, preftype, prefval) FROM stdin;
range	normal	10
hostmaster	normal	hostmaster.domain.tdl
prins	normal	ns1.domain.tdl
secns	normal	ns2.domain.tdl
transfer	normal	
PTR	record	off
WKS	record	off
HINFO	record	off
MINFO	record	off
TXT	record	off
RP	record	off
AFSDB	record	off
X25	record	off
ISDN	record	off
RT	record	off
NSAP	record	off
NSAP-PTR	record	off
SIG	record	off
KEY	record	off
PX	record	off
GPOS	record	off
LOC	record	off
NXT	record	off
EID	record	off
NIMLOC	record	off
SRV	record	off
ATMA	record	off
NAPTR	record	off
KX	record	off
CERT	record	off
A6	record	off
DNAME	record	off
SINK	record	off
OPT	record	off
APL	record	off
DS	record	off
SSHFP	record	off
RRSIG	record	off
NSEC	record	off
DNSKEY	record	off
TKEY	record	off
TSIG	record	off
IXFR	record	off
AXFR	record	off
MAILB	record	off
A	record	on
AAAA	record	on
CNAME	record	on
MX	record	on
NS	record	on
\.


ALTER TABLE ONLY options
    ADD CONSTRAINT options_pkey PRIMARY KEY (prefkey);

ALTER TABLE ONLY records
    ADD CONSTRAINT records_pkey PRIMARY KEY (id);

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);

ALTER TABLE ONLY users
    ADD CONSTRAINT users_username_key UNIQUE (username);

ALTER TABLE ONLY zones
    ADD CONSTRAINT zones_pkey PRIMARY KEY (id);
