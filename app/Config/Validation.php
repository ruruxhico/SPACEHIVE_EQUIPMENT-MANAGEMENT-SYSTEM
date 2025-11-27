<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
    // --------------------------------------------------------------------
    // USER Rules
    // --------------------------------------------------------------------
    public array $insert = [
        'firstname' => [
            'label' => 'First Name',
            'rules' => 'required|regex_match[/^[a-zA-Z\s]*$/]',
            'errors' => [
                'required' => 'First Name is required.',
                'regex_match' => "First Name can only contain alphabetic characters, spaces, apostrophes (') and dashes (-)."
            ]
        ],

        'middlename' => [
            'label' => 'Middle Name',
            'rules' => 'permit_empty|regex_match[/^[a-zA-Z\s]*$/]',
            'errors' => [
                'regex_match' => "Midle Name can only contain alphabetic characters, spaces, apostrophes (') and dashes (-)."
            ]
        ],

        'lastname' => [
            'label' => 'Last Name',
            'rules' => 'required|regex_match[/^[a-zA-Z\s]*$/]',
            'errors' => [
                'required' => 'Last Name is required.',
                'regex_match' => "Last Name can only contain alphabetic characters, spaces, apostrophes (') and dashes (-)."
            ]
        ],

        'email' => [
            'label' => 'Email Address',
            'rules' => 'required|valid_email|is_unique[users.email]',
            'errors' => [
                'required' => 'Email is required.',
                'valid_email' => "Email entered is invalid.",
                'is_unique' => "Email entered is already in use."
            ]
        ],

        'password' => [
            'label' => 'Password',
            'rules' => 'required|min_length[8]|max_length[50]',
            'errors' => [
                'required' => 'Password is required.',
                'min_length' => "Password must be at least 8 characters long.",
                'max_length' => "Password cannot exceed 50 characters long."
            ]
        ],

        'confirmpassword' => [
            'label' => 'Confirm Password',
            'rules' => 'matches[password]',
            'errors' => [
                'matches' => 'Confirm Password does not match the Password.',
            ]
        ],
    ];
    
    //--------------------------------------------------

    public array $edit = [
        'firstname' => [
            'label' => 'First Name',
            'rules' => 'required|regex_match[/^[a-zA-Z\s]*$/]',
            'errors' => [
                'required' => 'First Name is required.',
                'regex_match' => "First Name can only contain alphabetic characters, spaces, apostrophes (') and dashes (-)."
            ]
        ],

        'middlename' => [
            'label' => 'Middle Name',
            'rules' => 'permit_empty|regex_match[/^[a-zA-Z\s]*$/]',
            'errors' => [
                'regex_match' => "Midle Name can only contain alphabetic characters, spaces, apostrophes (') and dashes (-)."
            ]
        ],

        'lastname' => [
            'label' => 'Last Name',
            'rules' => 'required|regex_match[/^[a-zA-Z\s]*$/]',
            'errors' => [
                'required' => 'Last Name is required.',
                'regex_match' => "Last Name can only contain alphabetic characters, spaces, apostrophes (') and dashes (-)."
            ]
        ],

        'email' => [
            'label' => 'Email Address',
            'rules' => 'required|valid_email',
            'errors' => [
                'required' => 'Email is required.',
                'valid_email' => "Email entered is invalid.",
            ]
        ],

        'password' => [
            'label' => 'Password',
            'rules' => 'required|min_length[8]|max_length[50]',
            'errors' => [
                'required' => 'Password is required.',
                'min_length' => "Password must be at least 8 characters long.",
                'max_length' => "Password cannot exceed 50 characters long."
            ]
        ],

        'confirmpassword' => [
            'label' => 'Confirm Password',
            'rules' => 'matches[password]',
            'errors' => [
                'matches' => 'Confirm Password does not match the Password.',
            ]
        ],
    ];
}
