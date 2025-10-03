
# entities

- site
- theme
- component
- user
- role
- permission

## sites

- id
- name
- description
- domain
- theme_id
- created_at
- updated_at
- created_by
- updated_by

## theme

- id
- name
- description
...
- created_at
- updated_at
- created_by
- updated_by

## pages

- id
- name
- description
- content | json
- site_id
- slug
- created_at
- updated_at
- created_by
- updated_by

## component

- id
- name
- type
- content | json
- theme_id
- created_at
- updated_at
- created_by
- updated_by

## user

- id
- name
- email
- password
- role_id
- created_at
- updated_at
- created_by
- updated_by

## role

- id
- name
- description
- created_at
- updated_at
- created_by
- updated_by

## permission

- id
- name
- description
- content | json
- created_at
- updated_at
- created_by
- updated_by
