SELECT
    e1.username, e1.email1, j1.start_date, j1.salary, e2.company_name
FROM
    employee_info as e1, job_info as j1, employer_info as e2
WHERE 
    e1.permission_level = 'admin' AND
    e1.is_permission_level_active = 'true' AND
    e1.job_id = j1.job_id AND
    e1.employer_id = e2.employer_id
;
