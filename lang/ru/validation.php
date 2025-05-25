<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Поле :attribute должно быть принято',
    'accepted_if' => 'Поле :attribute должно быть принято, если :other равно :value.',
    'active_url' => 'Поле :attribute должно быть действительным URL',
    'after' => 'Поле :attribute должно быть датой после :date.',
    'after_or_equal' => 'Поле :attribute должно быть датой после или равной :date.',
    'alpha' => 'Поле :attribute должно содержать только буквы',
    'alpha_dash' => 'Поле :attribute должно содержать только буквы, цифры, тире и знаки подчеркивания',
    'alpha_num' => 'Поле :attribute должно содержать только буквы и цифры',
    'array' => 'Поле :attribute должно быть массивом',
    'ascii' => 'Поле :attribute должно содержать только однобайтовые алфавитно-цифровые символы и знаки.',
    'before' => 'Поле :attribute должно быть датой до :date.',
    'before_or_equal' => 'Поле :attribute должно быть датой, предшествующей или равной :date.',
    'between' => [
        'array' => 'Поле :attribute должно иметь от :min до :max элементов',
        'file' => 'Поле :attribute должно иметь размер от :min до :max килобайт.',
        'numeric' => 'Поле :attribute должно быть между :min и :max.',
        'string' => 'Поле :attribute должно быть между :min и :max символами',
    ],
    'boolean' => 'Поле :attribute должно быть true или false.',
    'can' => 'Поле :attribute содержит неавторизованное значение',
    'confirmed' => 'Подтверждение поля :attribute не совпадает.',
    'contains' => 'В поле :attribute отсутствует обязательное значение.',
    'current_password' => 'Пароль неверен.',
    'date' => 'Поле :attribute должно быть действительной датой',
    'date_equals' => 'Поле :attribute должно быть датой, равной :date.',
    'date_format' => 'Поле :attribute должно соответствовать формату :format.',
    'decimal' => 'Поле :attribute должно иметь :decimal десятичные знаки',
    'declined' => 'Поле :attribute должно быть отклонено.',
    'declined_if' => 'Поле :attribute должно быть отклонено, если :other равно :value.',
    'different' => 'Поле :attribute и :other должны быть разными.',
    'digits' => 'Поле :attribute должно быть :digits digits.',
    'digits_between' => 'Поле :attribute должно быть между :min и :max цифрами.',
    'dimensions' => 'Поле :attribute имеет недопустимые размеры изображения.',
    'distinct' => 'Поле :attribute имеет дублирующее значение.',
    'doesnt_end_with' => 'Поле :attribute не должно заканчиваться одним из следующих значений: :values.',
    'doesnt_start_with' => 'Поле :attribute не должно начинаться с одного из следующих значений: :values.',
    'email' => 'Поле :attribute должно быть действительным адресом электронной почты',
    'ends_with' => 'Поле :attribute должно заканчиваться одним из следующих значений: :values.',
    'enum' => 'Выбранный :attribute недействителен.',
    'exists' => 'Выбранный :attribute недействителен.',
    'extensions' => 'Поле :attribute должно иметь одно из следующих расширений: :values.',
    'file' => 'Поле :attribute должно быть файлом',
    'filled' => 'Поле :attribute должно иметь значение.',
    'gt' => [
        'array' => 'Поле :attribute должно иметь больше, чем :value items.',
        'file' => 'Поле :attribute должно быть больше, чем :value килобайт.',
        'numeric' => 'Поле :attribute должно быть больше, чем :value.',
        'string' => 'Поле :attribute должно быть больше, чем :value символов.'
    ],
    'gte' => [
        'array' => 'Поле :attribute должно иметь элементы :value или больше.',
        'file' => 'Поле :attribute должно быть больше или равно :value kilobytes.',
        'numeric' => 'Поле :attribute должно быть больше или равно :value.',
        'string' => 'Поле :attribute должно быть больше или равно :value символов.'
    ],
    'hex_color' => 'Поле :attribute должно быть правильным шестнадцатеричным цветом',
    'image' => 'Поле :attribute должно быть изображением.',
    'in' => 'Выбранный :атрибут недействителен.',
    'in_array' => 'Поле :attribute должно существовать в :other.',
    'integer' => 'Поле :attribute должно быть целым числом.',
    'ip' => 'Поле :attribute должно быть действительным IP-адресом',
    'ipv4' => 'Поле :attribute должно быть действительным IPv4-адресом',
    'ipv6' => 'Поле :attribute должно быть действительным IPv6-адресом.',
    'json' => 'Поле :attribute должно быть правильной строкой JSON.',
    'list' => 'Поле :attribute должно быть списком',
    'lowercase' => 'Поле :attribute должно быть строчным.',
    'lt' => [
        'array' => 'Поле :attribute должно иметь меньше, чем :value items.',
        'file' => 'Поле :attribute должно быть меньше, чем :value килобайт.',
        'numeric' => 'Поле :attribute должно быть меньше :value.',
        'string' => 'Поле :attribute должно быть меньше, чем :value символов.'
    ],
    'lte' => [
        'array' => 'Поле :attribute не должно иметь более чем :value элементов.',
        'file' => 'Поле :attribute должно быть меньше или равно :value kilobytes.',
        'numeric' => 'Поле :attribute должно быть меньше или равно :value.',
        'string' => 'Поле :attribute должно быть меньше или равно :value символов.'
    ],
    'mac_address' => 'Поле :attribute должно быть действительным MAC-адресом',
    'max' => [
        'array' => 'Поле :attribute не должно содержать более :max элементов',
        'file' => 'Поле :attribute не должно быть больше :max килобайт.',
        'numeric' => 'Поле :attribute не должно быть больше, чем :max.',
        'string' => 'Поле :attribute не должно быть больше, чем :max символов.',
    ],
    'max_digits' => 'Поле :attribute не должно содержать более :max цифр.',
    'mimes' => 'Поле :attribute должно быть файлом типа: :values.',
    'mimetypes' => 'Поле :attribute должно быть файлом типа: :values.',
    'min' => [
        'array' => 'Поле :attribute должно содержать не менее :min элементов',
        'file' => 'Поле :attribute должно иметь размер не менее :min килобайт',
        'numeric' => 'Поле :attribute должно быть не менее :min.',
        'string' => 'Поле :attribute должно быть не менее :min символов.'
    ],
    'min_digits' => 'Поле :attribute должно содержать не менее :min цифр.',
    'missing' => 'Поле :attribute должно отсутствовать',
    'missing_if' => 'Поле :attribute должно отсутствовать, если :other равно :value.',
    'missing_unless' => 'Поле :attribute должно отсутствовать, если :other не равно :value.',
    'missing_with' => 'Поле :attribute должно отсутствовать, если присутствует :values.',
    'missing_with_all' => 'Поле :attribute должно отсутствовать, если присутствуют :values.',
    'multiple_of' => 'Поле :attribute должно быть кратно :value.',
    'not_in' => 'Выбранный :attribute недействителен.',
    'not_regex' => 'Формат поля :attribute недействителен.',
    'numeric' => 'Поле :attribute должно быть числом.',
    'password' => [
        'letters' => 'Поле :attribute должно содержать хотя бы одну букву',
        'mixed' => 'Поле :attribute должно содержать как минимум одну заглавную и одну строчную букву',
        'numbers' => 'Поле :attribute должно содержать хотя бы одно число',
        'symbols' => 'Поле :attribute должно содержать хотя бы один символ',
        'uncompromised' => ':attribute был обнаружен в результате утечки данных. Пожалуйста, выберите другой :атрибут.'
    ],
    'present' => 'Поле :attribute должно присутствовать',
    'present_if' => 'Поле :attribute должно присутствовать, если :other равно :value.',
    'present_unless' => 'Поле :attribute должно присутствовать, если :other не равно :value.',
    'present_with' => 'Поле :attribute должно присутствовать, когда присутствует :values.',
    'present_with_all' => 'Поле :attribute должно присутствовать, когда присутствуют :values.',
    'prohibited' => 'Поле :attribute запрещено',
    'prohibited_if' => 'Поле :attribute запрещено, если :other равно :value.',
    'prohibited_if_accepted' => 'Поле :attribute запрещено, если :other принято.',
    'prohibited_if_declined' => 'Поле :attribute запрещено, когда :other отклоняется.',
    'prohibited_unless' => 'Поле :attribute запрещено, если :other не находится в :values.',
    'prohibits' => 'Поле :attribute запрещает присутствие :other.',
    'regex' => 'Формат поля :attribute недействителен.',
    'required' => 'Поле :attribute является обязательным.',
    'required_array_keys' => 'Поле :attribute должно содержать записи для: :values.',
    'required_if' => 'Поле :attribute обязательно, если :other равно :value.',
    'required_if_accepted' => 'Поле :attribute обязательно, если :other принято.',
    'required_if_declined' => 'Поле :attribute требуется, когда :other отклоняется.',
    'required_unless' => 'Поле :attribute обязательно, если только :other не находится в :values.',
    'required_with' => 'Поле :attribute обязательно для заполнения, если присутствует :values.',
    'required_with_all' => 'Поле :attribute обязательно для заполнения, если присутствуют :values.',
    'required_without' => 'Поле :attribute обязательно для заполнения, если :values не присутствует',
    'required_without_all' => 'Поле :attribute обязательно для заполнения, если ни одно из :values не присутствует',
    'same' => 'Поле :attribute должно совпадать с полем :other.',
    'size' => [
        'array' => 'Поле :attribute должно содержать элементы :size.',
        'file' => 'Поле :attribute должно быть :size килобайт.',
        'numeric' => 'Поле :attribute должно быть :size.',
        'string' => 'Поле :attribute должно быть :size символов.',
    ],
    'starts_with' => 'Поле :attribute должно начинаться с одного из следующих слов: :values.',
    'string' => 'Поле :attribute должно быть строкой.',
    'timezone' => 'Поле :attribute должно быть действительным часовым поясом.',
    'unique' => 'Поле :attribute уже было занято',
    'uploaded' => 'Не удалось загрузить :атрибут.',
    'uppercase' => 'Поле :attribute должно быть в верхнем регистре.',
    'url' => 'Поле :attribute должно быть правильным URL.',
    'ulid' => 'Поле :attribute должно быть действительным ULID.',
    'uuid' => 'Поле :attribute должно быть действительным UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
