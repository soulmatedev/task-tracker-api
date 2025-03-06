CREATE TABLE "Role" (
     "id" SERIAL PRIMARY KEY,
     "name" VARCHAR NOT NULL
);

CREATE TABLE "Account" (
     "email" VARCHAR PRIMARY KEY,
     "password" VARCHAR,
     "login" VARCHAR,
     "profile_picture_url" TEXT NULL,
     "role" INTEGER NULL REFERENCES "Role"("id")
);

CREATE TABLE "Status" (
    "id" SERIAL PRIMARY KEY,
    "name" VARCHAR NOT NULL
);

CREATE TABLE "Project" (
     "id" SERIAL PRIMARY KEY,
     "name" VARCHAR NOT NULL,
     "description" TEXT,
     "created_by" VARCHAR REFERENCES "Account"("email") ON DELETE SET NULL,
     "created_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "Task" (
     "id" SERIAL PRIMARY KEY,
     "title" VARCHAR NOT NULL,
     "description" TEXT,
     "project_id" INTEGER NOT NULL REFERENCES "Project"("id") ON DELETE CASCADE,
     "created_by" VARCHAR NOT NULL REFERENCES "Account"("email") ON DELETE SET NULL,
     "assigned_to" VARCHAR REFERENCES "Account"("email") ON DELETE SET NULL,
     "due_date" TIMESTAMP NULL,
     "status" INTEGER NULL REFERENCES "Status"("id"),
     "created_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
     "updated_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
