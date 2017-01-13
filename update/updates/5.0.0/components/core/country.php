<?php

/**
 * Cloudrexx
 *
 * @link      http://www.cloudrexx.com
 * @copyright Cloudrexx AG 2007-2017
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Cloudrexx" is a registered trademark of Cloudrexx AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

function _countryInstall() {
    global $objUpdate, $_CONFIG;

    if ($objUpdate->_isNewerVersion($_CONFIG['coreCmsVersion'], '5.0.0')) {
        try {
            // structure
            \Cx\Lib\UpdateUtil::table(
                DBPREFIX.'core_country_country',
                array(
                    'alpha2'     => array('type' => 'CHAR(2)', 'primary' => true),
                    'alpha3'     => array('type' => 'CHAR(3)', 'notnull' => true, 'default' => '', 'after' => 'alpha2'),
                    'ord'        => array('type' => 'INT(5)', 'unsigned' => true, 'notnull' => true, 'default' => '0', 'after' => 'alpha3')
                ),
                array(
                    'alpha3'     => array('fields' => array('alpha3'), 'type' => 'UNIQUE')
                ),
                'InnoDB'
            );
            // data
            \Cx\Lib\UpdateUtil::sql(
                "INSERT IGNORE INTO `".DBPREFIX."core_country_country` (`alpha2`, `alpha3`, `ord`) 
                VALUES 
                ('AF','AFG',0),
                ('AL','ALB',0),
                ('DZ','DZA',0),
                ('AS','ASM',0),
                ('AD','AND',0),
                ('AO','AGO',0),
                ('AI','AIA',0),
                ('AQ','ATA',0),
                ('AG','ATG',0),
                ('AR','ARG',0),
                ('AM','ARM',0),
                ('AW','ABW',0),
                ('AU','AUS',0),
                ('AT','AUT',0),
                ('AZ','AZE',0),
                ('BS','BHS',0),
                ('BH','BHR',0),
                ('BD','BGD',0),
                ('BB','BRB',0),
                ('BY','BLR',0),
                ('BE','BEL',0),
                ('BZ','BLZ',0),
                ('BJ','BEN',0),
                ('BM','BMU',0),
                ('BT','BTN',0),
                ('BO','BOL',0),
                ('BA','BIH',0),
                ('BW','BWA',0),
                ('BV','BVT',0),
                ('BR','BRA',0),
                ('IO','IOT',0),
                ('BN','BRN',0),
                ('BG','BGR',0),
                ('BF','BFA',0),
                ('BI','BDI',0),
                ('KH','KHM',0),
                ('CM','CMR',0),
                ('CA','CAN',0),
                ('CV','CPV',0),
                ('KY','CYM',0),
                ('CF','CAF',0),
                ('TD','TCD',0),
                ('CL','CHL',0),
                ('CN','CHN',0),
                ('CX','CXR',0),
                ('CC','CCK',0),
                ('CO','COL',0),
                ('KM','COM',0),
                ('CG','COG',0),
                ('CK','COK',0),
                ('CR','CRI',0),
                ('CI','CIV',0),
                ('HR','HRV',0),
                ('CU','CUB',0),
                ('CY','CYP',0),
                ('CZ','CZE',0),
                ('DK','DNK',0),
                ('DJ','DJI',0),
                ('DM','DMA',0),
                ('DO','DOM',0),
                ('TP','TMP',0),
                ('EC','ECU',0),
                ('EG','EGY',0),
                ('SV','SLV',0),
                ('GQ','GNQ',0),
                ('ER','ERI',0),
                ('EE','EST',0),
                ('ET','ETH',0),
                ('FK','FLK',0),
                ('FO','FRO',0),
                ('FJ','FJI',0),
                ('FI','FIN',0),
                ('FR','FRA',0),
                ('FX','FXX',0),
                ('GF','GUF',0),
                ('PF','PYF',0),
                ('TF','ATF',0),
                ('GA','GAB',0),
                ('GM','GMB',0),
                ('GE','GEO',0),
                ('DE','DEU',0),
                ('GH','GHA',0),
                ('GI','GIB',0),
                ('GR','GRC',0),
                ('GL','GRL',0),
                ('GD','GRD',0),
                ('GP','GLP',0),
                ('GU','GUM',0),
                ('GT','GTM',0),
                ('GN','GIN',0),
                ('GW','GNB',0),
                ('GY','GUY',0),
                ('HT','HTI',0),
                ('HM','HMD',0),
                ('HN','HND',0),
                ('HK','HKG',0),
                ('HU','HUN',0),
                ('IS','ISL',0),
                ('IN','IND',0),
                ('ID','IDN',0),
                ('IR','IRN',0),
                ('IQ','IRQ',0),
                ('IE','IRL',0),
                ('IL','ISR',0),
                ('IT','ITA',0),
                ('JM','JAM',0),
                ('JP','JPN',0),
                ('JO','JOR',0),
                ('KZ','KAZ',0),
                ('KE','KEN',0),
                ('KI','KIR',0),
                ('KP','PRK',0),
                ('KR','KOR',0),
                ('KW','KWT',0),
                ('KG','KGZ',0),
                ('LA','LAO',0),
                ('LV','LVA',0),
                ('LB','LBN',0),
                ('LS','LSO',0),
                ('LR','LBR',0),
                ('LY','LBY',0),
                ('LI','LIE',0),
                ('LT','LTU',0),
                ('LU','LUX',0),
                ('MO','MAC',0),
                ('MK','MKD',0),
                ('MG','MDG',0),
                ('MW','MWI',0),
                ('MY','MYS',0),
                ('MV','MDV',0),
                ('ML','MLI',0),
                ('MT','MLT',0),
                ('MH','MHL',0),
                ('MQ','MTQ',0),
                ('MR','MRT',0),
                ('MU','MUS',0),
                ('YT','MYT',0),
                ('MX','MEX',0),
                ('FM','FSM',0),
                ('MD','MDA',0),
                ('MC','MCO',0),
                ('MN','MNG',0),
                ('MS','MSR',0),
                ('MA','MAR',0),
                ('MZ','MOZ',0),
                ('MM','MMR',0),
                ('NA','NAM',0),
                ('NR','NRU',0),
                ('NP','NPL',0),
                ('NL','NLD',0),
                ('AN','ANT',0),
                ('NC','NCL',0),
                ('NZ','NZL',0),
                ('NI','NIC',0),
                ('NE','NER',0),
                ('NG','NGA',0),
                ('NU','NIU',0),
                ('NF','NFK',0),
                ('MP','MNP',0),
                ('NO','NOR',0),
                ('OM','OMN',0),
                ('PK','PAK',0),
                ('PW','PLW',0),
                ('PA','PAN',0),
                ('PG','PNG',0),
                ('PY','PRY',0),
                ('PE','PER',0),
                ('PH','PHL',0),
                ('PN','PCN',0),
                ('PL','POL',0),
                ('PT','PRT',0),
                ('PR','PRI',0),
                ('QA','QAT',0),
                ('RE','REU',0),
                ('RO','ROM',0),
                ('RU','RUS',0),
                ('RW','RWA',0),
                ('KN','KNA',0),
                ('LC','LCA',0),
                ('VC','VCT',0),
                ('WS','WSM',0),
                ('SM','SMR',0),
                ('ST','STP',0),
                ('SA','SAU',0),
                ('SN','SEN',0),
                ('SC','SYC',0),
                ('SL','SLE',0),
                ('SG','SGP',0),
                ('SK','SVK',0),
                ('SI','SVN',0),
                ('SB','SLB',0),
                ('SO','SOM',0),
                ('ZA','ZAF',0),
                ('GS','SGS',0),
                ('ES','ESP',0),
                ('LK','LKA',0),
                ('SH','SHN',0),
                ('PM','SPM',0),
                ('SD','SDN',0),
                ('SR','SUR',0),
                ('SJ','SJM',0),
                ('SZ','SWZ',0),
                ('SE','SWE',0),
                ('CH','CHE',0),
                ('SY','SYR',0),
                ('TW','TWN',0),
                ('TJ','TJK',0),
                ('TZ','TZA',0),
                ('TH','THA',0),
                ('TG','TGO',0),
                ('TK','TKL',0),
                ('TO','TON',0),
                ('TT','TTO',0),
                ('TN','TUN',0),
                ('TR','TUR',0),
                ('TM','TKM',0),
                ('TC','TCA',0),
                ('TV','TUV',0),
                ('UG','UGA',0),
                ('UA','UKR',0),
                ('AE','ARE',0),
                ('GB','GBR',0),
                ('US','USA',0),
                ('UM','UMI',0),
                ('UY','URY',0),
                ('UZ','UZB',0),
                ('VU','VUT',0),
                ('VA','VAT',0),
                ('VE','VEN',0),
                ('VN','VNM',0),
                ('VG','VGB',0),
                ('VI','VIR',0),
                ('WF','WLF',0),
                ('EH','ESH',0),
                ('YE','YEM',0),
                ('YU','YUG',0),
                ('ZR','ZAR',0),
                ('ZM','ZMB',0),
                ('ZW','ZWE',0)
            ");
        } catch (\Cx\Lib\UpdateException $e) {
            return \Cx\Lib\UpdateUtil::DefaultActionHandler($e);
        }
    }
    return true;
}