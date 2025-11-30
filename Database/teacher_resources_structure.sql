-- Database structure for teacher resources (folders and file links)
-- Compatible with existing education_platform database structure

-- Table for teacher folders
DROP TABLE IF EXISTS `teacher_folders`;
CREATE TABLE IF NOT EXISTS `teacher_folders` (
    `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
    `teacher_id` int UNSIGNED NOT NULL,
    `folder_name` varchar(255) NOT NULL,
    `description` text,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table for teacher file links
DROP TABLE IF EXISTS `teacher_links`;
CREATE TABLE IF NOT EXISTS `teacher_links` (
    `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
    `folder_id` int UNSIGNED NOT NULL,
    `teacher_id` int UNSIGNED NOT NULL,
    `link_title` varchar(255) NOT NULL,
    `link_url` text NOT NULL,
    `link_type` enum('drive','exam','document','video','other') DEFAULT 'other',
    `description` text,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `folder_id` (`folder_id`),
    KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Add foreign key constraints
ALTER TABLE `teacher_folders`
    ADD CONSTRAINT `teacher_folders_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `teacher_links`
    ADD CONSTRAINT `teacher_links_folder_fk` FOREIGN KEY (`folder_id`) REFERENCES `teacher_folders` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `teacher_links_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Table for teacher presentations
DROP TABLE IF EXISTS `teacher_presentations`;
CREATE TABLE IF NOT EXISTS `teacher_presentations` (
    `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
    `teacher_id` int UNSIGNED NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `teacher_id` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Table for presentation slides
DROP TABLE IF EXISTS `presentation_slides`;
CREATE TABLE IF NOT EXISTS `presentation_slides` (
    `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
    `presentation_id` int UNSIGNED NOT NULL,
    `slide_number` int NOT NULL,
    `title` varchar(255) NOT NULL,
    `content` longtext,
    `slide_type` enum('text','text_image','bullet_points','title_slide') DEFAULT 'text',
    `image_url` text,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `presentation_id` (`presentation_id`),
    KEY `slide_number` (`slide_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Add foreign key constraints for presentations
ALTER TABLE `teacher_presentations`
    ADD CONSTRAINT `teacher_presentations_teacher_fk` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `presentation_slides`
    ADD CONSTRAINT `presentation_slides_presentation_fk` FOREIGN KEY (`presentation_id`) REFERENCES `teacher_presentations` (`id`) ON DELETE CASCADE;

-- Insert sample data (optional)
-- Using existing teacher IDs from your database (teacher_id 4 = 'المعلم الأول', teacher_id 5 = 'المعلم الثاني')

-- Sample folders for المعلم الأول (ID: 4)
INSERT INTO `teacher_folders` (`teacher_id`, `folder_name`, `description`) VALUES 
(4, 'الأحياء - الصف التاسع', 'مجلد يحتوي على موارد الأحياء للصف التاسع'),
(4, 'امتحانات الأحياء', 'امتحانات شهرية ونهائية لمادة الأحياء'),
(4, 'فيديوهات تعليمية', 'مقاطع فيديو تعليمية للأحياء');

-- Sample folders for المعلم الثاني (ID: 5) 
INSERT INTO `teacher_folders` (`teacher_id`, `folder_name`, `description`) VALUES 
(5, 'العربي - الصف العاشر', 'مجلد يحتوي على موارد اللغة العربية للصف العاشر'),
(5, 'امتحانات العربي', 'امتحانات شهرية ونهائية لمادة اللغة العربية');

-- Sample links for المعلم الأول
INSERT INTO `teacher_links` (`folder_id`, `teacher_id`, `link_title`, `link_url`, `link_type`, `description`) VALUES 
(1, 4, 'كتاب الأحياء الالكتروني', 'https://drive.google.com/file/d/biology_book_example', 'drive', 'الكتاب المدرسي الالكتروني للأحياء'),
(1, 4, 'شرائح عرض الخلية', 'https://drive.google.com/presentation/d/cell_presentation', 'document', 'شرائح عرض تقديمي عن الخلية'),
(2, 4, 'امتحان الشهر الأول - الأحياء', 'https://drive.google.com/file/d/bio_exam1_example', 'exam', 'امتحان الأحياء للشهر الأول'),
(3, 4, 'فيديو شرح التنفس الخلوي', 'https://www.youtube.com/watch?v=cellular_respiration', 'video', 'فيديو تعليمي عن عملية التنفس الخلوي');

-- Sample links for المعلم الثاني  
INSERT INTO `teacher_links` (`folder_id`, `teacher_id`, `link_title`, `link_url`, `link_type`, `description`) VALUES 
(4, 5, 'كتاب اللغة العربية', 'https://drive.google.com/file/d/arabic_book_example', 'drive', 'الكتاب المدرسي للغة العربية'),
(4, 5, 'قصائد الشعر العربي', 'https://drive.google.com/folder/d/arabic_poetry_collection', 'document', 'مجموعة من قصائد الشعر العربي'),
(5, 5, 'امتحان الشهر الأول - العربي', 'https://drive.google.com/file/d/arabic_exam1_example', 'exam', 'امتحان اللغة العربية للشهر الأول');

-- Sample presentations
INSERT INTO `teacher_presentations` (`teacher_id`, `title`, `description`) VALUES 
(4, 'مقدمة في علم الأحياء', 'عرض تقديمي يشرح أساسيات علم الأحياء للطلاب'),
(4, 'الخلية النباتية والحيوانية', 'عرض مقارن بين الخلية النباتية والحيوانية'),
(5, 'قواعد اللغة العربية', 'عرض تقديمي عن قواعد النحو والصرف'),
(5, 'الشعر العربي الكلاسيكي', 'مقدمة عن الشعر العربي وبحوره');

-- Sample slides for presentations
INSERT INTO `presentation_slides` (`presentation_id`, `slide_number`, `title`, `content`, `slide_type`, `image_url`) VALUES 
(1, 1, 'مقدمة في علم الأحياء', 'مرحباً بكم في درس علم الأحياء', 'title_slide', NULL),
(1, 2, 'ما هو علم الأحياء؟', 'علم الأحياء هو العلم الذي يدرس الكائنات الحية وعملياتها الحيوية', 'text', NULL),
(1, 3, 'فروع علم الأحياء', '• علم الخلايا\n• علم الوراثة\n• علم البيئة\n• علم التشريح', 'bullet_points', NULL),
(2, 1, 'الخلية النباتية والحيوانية', 'مقارنة بين أنواع الخلايا', 'title_slide', NULL),
(2, 2, 'الخلية النباتية', 'تحتوي على جدار خلوي وبلاستيدات خضراء', 'text_image', 'https://example.com/plant_cell.jpg'),
(3, 1, 'قواعد اللغة العربية', 'أساسيات النحو والصرف', 'title_slide', NULL),
(3, 2, 'أقسام الكلام', '• الاسم\n• الفعل\n• الحرف', 'bullet_points', NULL);