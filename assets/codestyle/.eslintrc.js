module.exports = {
    "env": {
        "browser": true,
        "es6": true,
        "jquery": true
    },
    "extends": "eslint:recommended",
    "rules": {
        "indent": [
            "error",
            "tab"
        ],
        "quotes": [
            "error",
            "single"
        ],
        "semi": [
            "error",
            "always"
        ],
        "no-unsafe-negation": [
            "error"
        ],
        "no-undef": [
            "error",
            {
                "typeof": false
            }
        ],
        "no-template-curly-in-string": [
            "error"
        ],
        "radix": [
            "error"
        ],
        "yoda": [
            "error"
        ],
        "wrap-iife": [
            "error"
        ],
        "no-script-url": [
            "error"
        ],
        "no-sequences": [
            "error"
        ],
        "consistent-return": [
            "error"
        ],
        "curly": [
            "error"
        ],
        "dot-location": [
            "error",
            "property"
        ],
        "eqeqeq": [
            "error"
        ],
        "no-alert": [
            "error"
        ],
        "no-alert": [
            "error"
        ],
        "no-multi-spaces": [
            "error"
        ],
        "no-unsafe-negation": [
            "error"
        ],
        "valid-jsdoc": [
            "error"
        ],
        "strict": [
            "error"
        ],
        "no-undefined": [
            "error"
        ],
        "array-bracket-spacing": [
            "error",
            "never"
        ],
        "block-spacing": [
            "error"
        ],
        "brace-style": [
            "error"
        ],
        "comma-dangle": [
            "error",
            "never"
        ],
        "comma-spacing": [
            "error"
        ],
        "comma-style": [
            "error"
        ],
        "func-call-spacing": [
            "error"
        ],
        "indent": [
            "error",
            "tab"
        ],
        "key-spacing": [
            "error"
        ],
        "keyword-spacing": [
            "error"
        ],
        "new-parens": [
            "error"
        ],
        "newline-before-return": [
            "error"
        ],
        "no-mixed-operators": [
            "error"
        ],
        "no-multiple-empty-lines": [
            "error",
            {
                "max": 1
            }
        ],
        "no-trailing-spaces": [
            "error"
        ],
        "no-underscore-dangle": [
            "error"
        ],
        "no-whitespace-before-property": [
            "error"
        ],
        "padded-blocks": [
            "error",
            "never"
        ],
        "quote-props": [
            "error",
            "consistent"
        ],
        "require-jsdoc": [
            "error",
            {
                "require": {
                    "FunctionDeclaration": true,
                    "MethodDefinition": true,
                    "ClassDeclaration": false,
                    "ArrowFunctionExpression": false
                }
            }
        ],
        "semi-spacing": [
            "error",
            {
                "before": false,
                "after": true
            }
        ],
        "semi": [
            "error"
        ],
        "no-unexpected-multiline": [
            "error"
        ],
        "no-unreachable": [
            "error"
        ],
        "space-before-blocks": [
            "error"
        ],
        "space-before-function-paren": [
            "error",
            "always"
        ],
        "space-in-parens": [
            "error",
            "never"
        ],
        "space-infix-ops": [
            "error",
            {
                "int32Hint": false
            }
        ],
		"no-global-assign": [
			"error",
			{
				"exceptions": ["becurious"]
			}
		]
    }
};
