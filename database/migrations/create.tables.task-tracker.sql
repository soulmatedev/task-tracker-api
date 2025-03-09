CREATE TABLE "Role" (
                        "id" SERIAL PRIMARY KEY,
                        "name" VARCHAR NOT NULL
);

CREATE TABLE "Account" (
                           "id" SERIAL PRIMARY KEY,
                           "email" VARCHAR UNIQUE NOT NULL,
                           "password" VARCHAR,
                           "login" VARCHAR,
                           "profilePictureUrl" TEXT NULL,
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
                           "createdBy" INTEGER REFERENCES "Account"("id") ON DELETE SET NULL,
                           "createdAt" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE "ProjectAccount" (
                                  "id" SERIAL PRIMARY KEY,
                                  "projectId" INTEGER NOT NULL,
                                  "accountId" INTEGER NOT NULL
);

CREATE TABLE "Task" (
                        "id" SERIAL PRIMARY KEY,
                        "title" VARCHAR NOT NULL,
                        "description" TEXT,
                        "projectId" INTEGER NOT NULL REFERENCES "Project"("id") ON DELETE CASCADE,
                        "createdBy" INTEGER NOT NULL REFERENCES "Account"("id") ON DELETE SET NULL,
                        "assignedTo" INTEGER REFERENCES "Account"("id") ON DELETE SET NULL,
                        "dueDate" TIMESTAMP NULL,
                        "status" INTEGER NULL REFERENCES "Status"("id"),
                        "createdAt" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        "updatedAt" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE "ProjectAccount"
    ADD CONSTRAINT "project_account_projectId_foreign" FOREIGN KEY ("projectId") REFERENCES "Project" ("id") ON DELETE CASCADE;

ALTER TABLE "ProjectAccount"
    ADD CONSTRAINT "project_account_accountId_foreign" FOREIGN KEY ("accountId") REFERENCES "Account" ("id") ON DELETE CASCADE;
