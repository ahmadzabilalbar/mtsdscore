<?php
// PHP CLI Data Generator
error_reporting(E_ALL);
ini_set('display_errors', 1);

$subject_map = [
    'BM' => 'Bahasa Melayu',
    'BI' => 'Bahasa Inggeris',
    'SEJ' => 'Sejarah',
    'GEO' => 'Geografi',
    'BA' => 'Bahasa Arab',
    'PI' => 'Pendidikan Islam',
    'MATE' => 'Matematik',
    'SNS' => 'Sains',
    'RBT' => 'Reka Bentuk & Teknologi',
    'HQ' => 'Hifz Al-Quran',
    'MQ' => 'Maharat Al-Quran',
    'FEQ' => 'Feqah',
    'HAD' => 'Hadis',
    'TAU' => 'Tauhid',
    'TAF' => 'Tafsir',
    'PJK' => 'Pendidikan Jasmani & Kesihatan',
    'PSV' => 'Pendidikan Seni Visual'
];

function getGrade($score) {
    if ($score === null || $score === '') return ['grade' => '-', 'gp' => null];
    $score = (float)$score;
    if ($score >= 85) return ['grade' => 'A', 'gp' => 1];
    if ($score >= 70) return ['grade' => 'B', 'gp' => 2];
    if ($score >= 60) return ['grade' => 'C', 'gp' => 3];
    if ($score >= 50) return ['grade' => 'D', 'gp' => 4];
    if ($score >= 40) return ['grade' => 'E', 'gp' => 5];
    return ['grade' => 'F', 'gp' => 6];
}

$files = glob(__DIR__ . '/csv/*.csv');
$students = [];
$batches = [];
$forms = [];
$exams = [];
$genders = [];
$active_subjects = [];

foreach ($files as $file) {
    $filename = basename($file);
    if (preg_match('/^([^-]+)-([^-]+)-([^-]+)-([^-.]+)\.csv$/i', $filename, $matches)) {
        $batch = strtoupper($matches[1]);
        $form = strtoupper($matches[2]);
        $exam = strtoupper($matches[3]);
        $gender = strtoupper($matches[4]);

        $batches[$batch] = true;
        $forms[$form] = true;
        $exams[$exam] = true;
        $genders[$gender] = true;

        if (($handle = fopen($file, 'r')) !== false) {
            $header = fgetcsv($handle);
            if (!$header) {
                fclose($handle);
                continue;
            }
            
            $subjects_list = [];
            for ($i = 1; $i < count($header); $i++) {
                $sub = trim($header[$i]);
                if ($sub !== '') {
                    $subjects_list[$i] = $sub;
                    $active_subjects[$sub] = true;
                }
            }

            while (($row = fgetcsv($handle)) !== false) {
                if (empty($row) || trim($row[0]) === '') {
                    continue;
                }
                $name = trim($row[0]);
                $scores = [];
                $total_marks = 0;
                $subjects_count = 0;
                $grades_count = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'F' => 0];
                $sum_gp = 0;
                $gp_count = 0;

                foreach ($subjects_list as $index => $sub) {
                    $val = isset($row[$index]) ? trim($row[$index]) : '';
                    if ($val !== '' && is_numeric($val)) {
                        $score = (float)$val;
                        $scores[$sub] = $score;
                        $total_marks += $score;
                        $subjects_count++;
                        
                        $grade_info = getGrade($score);
                        $grades_count[$grade_info['grade']]++;
                        $sum_gp += $grade_info['gp'];
                        $gp_count++;
                    } else {
                        $scores[$sub] = null;
                    }
                }

                $average = $subjects_count > 0 ? round($total_marks / $subjects_count, 2) : 0;
                $gpp = $gp_count > 0 ? round($sum_gp / $gp_count, 2) : 0;

                $students[] = [
                    'name' => $name,
                    'batch' => $batch,
                    'form' => $form,
                    'exam' => $exam,
                    'gender' => $gender,
                    'scores' => $scores,
                    'total' => $total_marks,
                    'average' => $average,
                    'gpp' => $gpp,
                    'subjects_count' => $subjects_count,
                    'grades_count' => $grades_count
                ];
            }
            fclose($handle);
        }
    }
}

$batches = array_keys($batches);
sort($batches);
$forms = array_keys($forms);
sort($forms);
$exams = array_keys($exams);
sort($exams);
$genders = array_keys($genders);
sort($genders);

$subject_order = array_keys($subject_map);
$sorted_active_subjects = [];
foreach ($subject_order as $sub) {
    if (isset($active_subjects[$sub])) {
        $sorted_active_subjects[$sub] = $subject_map[$sub];
        unset($active_subjects[$sub]);
    }
}
foreach ($active_subjects as $sub => $val) {
    $sorted_active_subjects[$sub] = $sub;
}

$data = [
    'batches' => $batches,
    'forms' => $forms,
    'exams' => $exams,
    'genders' => $genders,
    'subjects' => $sorted_active_subjects,
    'students' => $students
];

$jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
$jsContent = "const MTSD_DATA = " . $jsonData . ";\n";

$targetFile = __DIR__ . '/data.js';
if (file_put_contents($targetFile, $jsContent) !== false) {
    echo "SUCCESS: Compiled data of " . count($students) . " student records into data.js\n";
} else {
    echo "ERROR: Failed to write data.js\n";
}
