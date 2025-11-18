<?php
namespace App\Helpers;

/**
 * Helper de Validación
 */
class ValidationHelper
{
    public static function validateCedula($cedula)
    {
        $cedula = preg_replace('/\D/', '', $cedula);
        return strlen($cedula) >= 6 && strlen($cedula) <= 10;
    }
    
    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function validatePhone($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);
        
        if (strlen($phone) === 7) {
            return true;
        }
        
        if (strlen($phone) === 10 && $phone[0] === '3') {
            return true;
        }
        
        return false;
    }
    
    public static function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    
    public static function validateMinAge($birthdate, $minAge = 18)
    {
        $today = new \DateTime();
        $birth = new \DateTime($birthdate);
        $age = $today->diff($birth)->y;
        return $age >= $minAge;
    }
    
    public static function sanitizeString($string)
    {
        return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
    }
    
    public static function validateLength($string, $min = null, $max = null)
    {
        $length = mb_strlen($string);
        
        if ($min !== null && $length < $min) {
            return false;
        }
        
        if ($max !== null && $length > $max) {
            return false;
        }
        
        return true;
    }
    
    public static function validateAlpha($string)
    {
        return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $string);
    }
    
    public static function validateNumeric($string)
    {
        return preg_match('/^[0-9]+$/', $string);
    }
    
    public static function validateGender($gender)
    {
        $validGenders = ['M', 'F', 'Otro'];
        return in_array($gender, $validGenders);
    }
    
    public static function validateNotEmpty($value)
    {
        if (is_array($value)) {
            return !empty($value);
        }
        return trim($value) !== '';
    }
    
    public static function validateBirthdate($date)
    {
        if (!self::validateDate($date)) {
            return false;
        }
        
        $birth = new \DateTime($date);
        $today = new \DateTime();
        
        return $birth <= $today;
    }
}