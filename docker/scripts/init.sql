create schema if not exists crypto collate latin1_swedish_ci;

create table if not exists crypto.single_cryptocurrency
(
    id                int auto_increment
        primary key,
    cryptocurrency_id varchar(50)          not null,
    address           varchar(50)          not null,
    name              varchar(10)          not null,
    price             float                not null,
    chain             varchar(20)          not null,
    holders           int                  null,
    percentage        float                null,
    occured_on        datetime             null,
    isComplete        tinyint(1) default 0 null,
    isBlacklisted     tinyint(1) default 0 null,
    isAlertSent       tinyint(1) default 0 null,
    constraint single_cryptocurrency_address_uindex
        unique (address),
    constraint single_cryptocurrency_cryptocurrency_id_uindex
        unique (cryptocurrency_id),
    constraint single_cryptocurrency_id_uindex
        unique (id),
    constraint single_cryptocurrency_name_uindex
        unique (name)
)
    collate = utf8mb4_unicode_ci;


INSERT INTO single_cryptocurrency (id, cryptocurrency_id, address, name, price, chain, holders, percentage, occured_on, isComplete, isBlacklisted, isAlertSent) VALUES (302, 'ad59199b-6567-4549-a668-29638857f558', '0x39af062b155978f1d41b299601defac54e94cbd8', 'megg', 12.382, 'wbnb', null, null, '2022-08-04 08:09:42',false , true , false );
INSERT INTO single_cryptocurrency (id, cryptocurrency_id, address, name, price, chain, holders, percentage, occured_on, isComplete, isBlacklisted, isAlertSent) VALUES (308, 'dd585fdc-4c5a-4290-8942-af2b8af452d1', '0x2ae3c31852facbf82db7f20964e1879ea571cfb5', 'kirby', 10.251, 'wbnb', null, null, '2022-08-04 11:11:21',false , true , false );
INSERT INTO single_cryptocurrency (id, cryptocurrency_id, address, name, price, chain, holders, percentage, occured_on, isComplete, isBlacklisted, isAlertSent) VALUES (310, '9c9c0106-1dd8-447c-b3db-25d9a3fa8cab', '0x17265c1edc6c65b464ffef01cadfdd1e0678cd5c', 'hle', 153.132, 'wbnb', null, null, '2022-08-04 11:12:23',false , true , false );
INSERT INTO single_cryptocurrency (id, cryptocurrency_id, address, name, price, chain, holders, percentage, occured_on, isComplete, isBlacklisted, isAlertSent) VALUES (311, '4bbfad1c-ffd7-4cba-8b28-9ecf669339ea', '0xae048cc5a452ab96ceca58c33f6ba34de58e56c0', 'amf', 43.527, 'wbnb', null, null, '2022-08-04 11:12:48',false , true , false );
INSERT INTO single_cryptocurrency (id, cryptocurrency_id, address, name, price, chain, holders, percentage, occured_on, isComplete, isBlacklisted, isAlertSent) VALUES (312, '686c7444-269f-4fae-8e9b-c39aed1e979e', '0x4ac53da58da5bcf17d1597726fcdaf238cd69bda', 'xki', 87.674, 'wbnb', null, null, '2022-08-04 11:14:11',false , true , false );
INSERT INTO single_cryptocurrency (id, cryptocurrency_id, address, name, price, chain, holders, percentage, occured_on, isComplete, isBlacklisted, isAlertSent) VALUES (313, '356b2350-5ba9-49a2-a759-9619d82512f0', '0xc1335459c5f43948b4989020b5fcbf4947b3610a', 'rba', 239.191, 'wbnb', null, null, '2022-08-04 11:45:55',false , true , false );
INSERT INTO single_cryptocurrency (id, cryptocurrency_id, address, name, price, chain, holders, percentage, occured_on, isComplete, isBlacklisted, isAlertSent) VALUES (317, '2f7887da-842e-40c8-9501-c0d0d93c0ee6', '0x6e75c56819cd935b3410dd3edd66d656e46cfa85', 'tsc', 43.037, 'wbnb', null, null, '2022-08-04 11:47:08',false , true , false );
INSERT INTO single_cryptocurrency (id, cryptocurrency_id, address, name, price, chain, holders, percentage, occured_on, isComplete, isBlacklisted, isAlertSent) VALUES (322, 'ff08dcad-546a-488d-85e9-6de33cd7f1d6', '0xab0a45c60d3059ac8d7144555b7714281802ebee', 'rfg', 18.017, 'wbnb', null, null, '2022-08-04 12:52:12',false , true , false );
INSERT INTO single_cryptocurrency (id, cryptocurrency_id, address, name, price, chain, holders, percentage, occured_on, isComplete, isBlacklisted, isAlertSent) VALUES (330, 'd23e1b66-7667-48e7-ad0f-5e94e568c724', '0xddcabaa1fe17f1fbb878eb8061a5bc260c511f36', 'fsc', 239.5, 'wbnb', null, null, '2022-08-04 13:19:17',false , true , false );
INSERT INTO single_cryptocurrency (id, cryptocurrency_id, address, name, price, chain, holders, percentage, occured_on, isComplete, isBlacklisted, isAlertSent) VALUES (293, 'c3075e93-302d-4ea0-81dd-5d21ae97b52b', '0x361c60b7c2828fcab80988d00d1d542c83387b50', 'dfi', 3044.73, 'busd', null, null, '2022-08-04 07:42:42', false , true , false);
INSERT INTO single_cryptocurrency (id, cryptocurrency_id, address, name, price, chain, holders, percentage, occured_on, isComplete, isBlacklisted, isAlertSent) VALUES (334, '8f009532-2cb1-4c90-a496-edab47d3a58d', '0x37eca5faa4ae5ff2a591ee477c25b59d33a2ade7', 'bmt', 42.913, 'wbnb', null, null, '2022-08-04 13:21:28',false , true , false );
INSERT INTO single_cryptocurrency (id, cryptocurrency_id, address, name, price, chain, holders, percentage, occured_on, isComplete, isBlacklisted, isAlertSent) VALUES (337, 'e79ff309-e34c-4ed4-806d-f37a67e60a63', '0x8a648ee1d6733d479a4e64f487509bc6b7f42b1c', 'fbbc', 239.317, 'wbnb', null, null, '2022-08-04 14:50:23',false , true , false );
INSERT INTO single_cryptocurrency (id, cryptocurrency_id, address, name, price, chain, holders, percentage, occured_on, isComplete, isBlacklisted, isAlertSent) VALUES (340, '63ee689d-658b-4234-8440-7dd78cfdf877', '0xea4bcfff56ef4aedd7f41fc260f13f2eee7defce', 'encwta', 21, 'wbnb', null, null, '2022-08-04 14:52:48',false , true , false );
INSERT INTO single_cryptocurrency (id, cryptocurrency_id, address, name, price, chain, holders, percentage, occured_on, isComplete, isBlacklisted, isAlertSent) VALUES (341, '3c652622-168c-4fb5-9148-cca86d8d23e6', '0xb5fef7a0937cf4b04529510194bc22fb604d0e16', 'velox', 16.019, 'wbnb', null, null, '2022-08-04 14:54:34',false , true , false );
INSERT INTO single_cryptocurrency (id, cryptocurrency_id, address, name, price, chain, holders, percentage, occured_on, isComplete, isBlacklisted, isAlertSent) VALUES (342, 'e6efbe2b-6ce4-4ec8-b653-2e3864108929', '0x03e9132e748880cdc3ba85a72243c28c2194a2c9', 'snap', 18.347, 'wbnb', null, null, '2022-08-04 14:54:51',false , true , false );

