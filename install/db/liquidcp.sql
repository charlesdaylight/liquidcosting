CREATE TABLE IF NOT EXISTS `cepf_liquidcp_rule_set` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `version` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'ACTIVE',
  `data_json` longtext NOT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_liquidcp_rule_set_code_version` (`code`, `version`)
) DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cepf_liquidcp_engine_run` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `record_id` bigint DEFAULT NULL,
  `quote_id` bigint DEFAULT NULL,
  `rule_set_id` bigint NOT NULL,
  `request_json` longtext NOT NULL,
  `response_json` longtext NOT NULL,
  `engine_version` varchar(100) NOT NULL,
  `duration_ms` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `cepf_liquidcp_quote_meta` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `record_id` bigint DEFAULT NULL,
  `engine_run_id` bigint DEFAULT NULL,
  `quote_number` varchar(100) DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `client_email` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `quote_title` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'draft',
  `rule_set_version` varchar(50) DEFAULT NULL,
  `engine_version` varchar(100) DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `input_json` longtext NOT NULL,
  `response_json` longtext NOT NULL,
  `total_due` decimal(18,4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8mb4;
