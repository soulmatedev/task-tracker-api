CREATE TABLE "Role" (
     "id" SERIAL PRIMARY KEY,
     "name" VARCHAR NOT NULL
);

CREATE TABLE "User" (
     "id" SERIAL PRIMARY KEY,
     "login" VARCHAR NOT NULL UNIQUE,
     "email" VARCHAR NOT NULL UNIQUE,
     "password" VARCHAR NOT NULL,
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
     "created_by" INTEGER REFERENCES "User"("id") ON DELETE SET NULL,
     "created_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "Task" (
      "id" SERIAL PRIMARY KEY,
      "title" VARCHAR NOT NULL,
      "description" TEXT,
      "project_id" INTEGER NOT NULL REFERENCES "Project"("id") ON DELETE CASCADE,
      "created_by" INTEGER NOT NULL REFERENCES "User"("id") ON DELETE SET NULL,
      "assigned_to" INTEGER REFERENCES "User"("id") ON DELETE SET NULL,
      "due_date" TIMESTAMP NULL,
      "status" INTEGER NULL REFERENCES "Status"("id"),
      "created_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      "updated_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
