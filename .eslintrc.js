module.exports = {
  env: {
    browser: true,
    es6: true,
  },
  extends: [
    'airbnb-base',
    'plugin:vue/essential',
  ],
  globals: {
    Atomics: 'readonly',
    SharedArrayBuffer: 'readonly',
  },
  parserOptions: {
    ecmaVersion: 2018,
    sourceType: 'module',
  },
  plugins: [
    'vue',
  ],
  rules: {
    "import/no-named-as-default-member": 0,
    "import/no-named-as-default": 0,
    "import/extensions": 0,
    "import/no-unresolved": 0,
    "no-param-reassign": [2, { "props": false }],
  },
};
