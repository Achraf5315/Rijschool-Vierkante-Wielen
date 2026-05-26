<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Address', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Street', 55);
            $table->string('HouseNumber', 5);
            $table->string('PostalCode', 6);
            $table->string('City', 30);
            $table->boolean('IsActive')->default(true);
            $table->string('Notes', 255)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('Contact', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('FirstName', 100);
            $table->string('LastName', 100);
            $table->string('Phone', 13);
            $table->date('DateOfBirth');
            $table->unsignedInteger('AddressId');
            $table->unsignedBigInteger('UserId')->unique();
            $table->boolean('IsActive')->default(true);
            $table->string('Notes', 255)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('AddressId')->references('Id')->on('Address')->cascadeOnDelete();
            $table->foreign('UserId')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::create('Client', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('ContactId');
            $table->string('BSN', 9)->nullable()->unique();
            $table->string('LicenseCategory', 10)->default('B');
            $table->boolean('IsActive')->default(true);
            $table->string('Notes', 255)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('ContactId')->references('Id')->on('Contact')->cascadeOnDelete();
        });

        Schema::create('Instructor', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('ContactId');
            $table->string('LicenseNumber', 50)->unique();
            $table->string('Certification', 100);
            $table->unsignedInteger('MaxStudentsPerDay')->default(8);
            $table->decimal('HourlyRate', 8, 2);
            $table->boolean('IsActive')->default(true);
            $table->string('Notes', 255)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('ContactId')->references('Id')->on('Contact')->cascadeOnDelete();
        });

        Schema::create('Vehicle', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('LicensePlate', 20)->unique();
            $table->string('Brand', 50);
            $table->string('Model', 50);
            $table->unsignedInteger('Year');
            $table->enum('Transmission', ['Manual', 'Automatic'])->default('Manual');
            $table->string('Category', 10)->default('B');
            $table->boolean('IsActive')->default(true);
            $table->string('Notes', 255)->nullable();
            $table->date('APKExpiry');
            $table->date('InsuranceExpiry');
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('InstructorVehicle', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('InstructorId');
            $table->unsignedInteger('VehicleId');
            $table->boolean('IsActive')->default(true);
            $table->string('Notes', 255)->nullable();
            $table->dateTime('AssignedAt')->useCurrent();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('InstructorId')->references('Id')->on('Instructor')->cascadeOnDelete();
            $table->foreign('VehicleId')->references('Id')->on('Vehicle')->cascadeOnDelete();
        });

        Schema::create('DrivingPackage', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Name', 150);
            $table->string('Description', 255);
            $table->unsignedInteger('LessonCount');
            $table->unsignedInteger('LessonDuration')->default(60);
            $table->decimal('Price', 10, 2);
            $table->string('Category', 10)->default('B');
            $table->boolean('IsActive')->default(true);
            $table->string('Notes', 255)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('ClientPackage', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('ClientId');
            $table->unsignedInteger('PackageId');
            $table->unsignedInteger('LessonsUsed')->default(0);
            $table->boolean('IsCompleted')->default(false);
            $table->boolean('IsActive')->default(true);
            $table->string('Notes', 255)->nullable();
            $table->dateTime('PurchasedAt')->useCurrent();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('ClientId')->references('Id')->on('Client')->cascadeOnDelete();
            $table->foreign('PackageId')->references('Id')->on('DrivingPackage')->cascadeOnDelete();
        });

        Schema::create('Lesson', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('ClientId');
            $table->unsignedInteger('InstructorId');
            $table->unsignedInteger('VehicleId');
            $table->unsignedInteger('ClientPackageId')->nullable();
            $table->dateTime('StartTime');
            $table->dateTime('EndTime');
            $table->string('Location', 255)->nullable();
            $table->enum('Status', [
                'Open',
                'Planned',
                'Confirmed',
                'InProgress',
                'Completed',
                'CancelledByClient',
                'CancelledByInstructor',
            ])->default('Open');
            $table->boolean('IsActive')->default(true);
            $table->string('Notes', 255)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('ClientId')->references('Id')->on('Client')->cascadeOnDelete();
            $table->foreign('InstructorId')->references('Id')->on('Instructor')->cascadeOnDelete();
            $table->foreign('VehicleId')->references('Id')->on('Vehicle')->cascadeOnDelete();
            $table->foreign('ClientPackageId')->references('Id')->on('ClientPackage')->nullOnDelete();
        });

        Schema::create('Invoice', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('InvoiceNumber', 50)->unique();
            $table->unsignedInteger('ClientId');
            $table->unsignedInteger('InstructorId');
            $table->date('IssueDate')->useCurrent();
            $table->date('DueDate');
            $table->decimal('Subtotal', 10, 2);
            $table->decimal('VATRate', 5, 2)->default(21.00);
            $table->decimal('VATAmount', 10, 2);
            $table->decimal('TotalAmount', 10, 2);
            $table->enum('Status', ['Draft', 'Sent', 'Paid', 'Overdue', 'Cancelled'])->default('Draft');
            $table->boolean('IsActive')->default(true);
            $table->string('Notes', 255)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('ClientId')->references('Id')->on('Client')->cascadeOnDelete();
            $table->foreign('InstructorId')->references('Id')->on('Instructor')->cascadeOnDelete();
        });

        Schema::create('InvoiceLine', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('InvoiceId');
            $table->string('Description', 255);
            $table->unsignedInteger('Quantity')->default(1);
            $table->decimal('UnitPrice', 10, 2);
            $table->decimal('LineTotal', 10, 2);
            $table->unsignedInteger('LessonId');
            $table->unsignedInteger('PackageId');
            $table->boolean('IsActive')->default(true);
            $table->string('Notes', 255)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('InvoiceId')->references('Id')->on('Invoice')->cascadeOnDelete();
            $table->foreign('LessonId')->references('Id')->on('Lesson')->cascadeOnDelete();
            $table->foreign('PackageId')->references('Id')->on('DrivingPackage')->cascadeOnDelete();
        });

        Schema::create('Payment', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('InvoiceId');
            $table->decimal('Amount', 10, 2);
            $table->enum('Method', ['iDEAL', 'CreditCard', 'BankTransfer', 'Cash', 'Tikkie']);
            $table->string('TransactionRef', 150);
            $table->enum('Status', ['Pending', 'Completed', 'Failed', 'Refunded'])->default('Pending');
            $table->boolean('IsActive')->default(true);
            $table->string('Notes', 255)->nullable();
            $table->dateTime('PaymentDate')->useCurrent();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('InvoiceId')->references('Id')->on('Invoice')->cascadeOnDelete();
        });

        Schema::create('Notification', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedBigInteger('UserId');
            $table->enum('Type', [
                'LessonReminder',
                'LessonConfirmed',
                'LessonCancelled',
                'InvoiceGenerated',
                'PaymentReceived',
                'SystemMessage',
                'PackageLowBalance',
            ]);
            $table->string('Title', 150);
            $table->string('Message', 255);
            $table->boolean('IsRead')->default(false);
            $table->boolean('IsActive')->default(true);
            $table->string('Notes', 255)->nullable();
            $table->dateTime('SentAt')->useCurrent();
            $table->dateTime('ReadAt')->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('UserId')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::create('ExamRequest', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('ClientId');
            $table->unsignedInteger('InstructorId');
            $table->date('RequestedDate');
            $table->date('ExamDate');
            $table->string('ExamLocation', 255);
            $table->enum('ExamType', ['Theory', 'Practical']);
            $table->enum('Status', ['Requested', 'Scheduled', 'Passed', 'Failed', 'Withdrawn'])->default('Requested');
            $table->boolean('IsActive')->default(true);
            $table->string('Notes', 255)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('ClientId')->references('Id')->on('Client')->cascadeOnDelete();
            $table->foreign('InstructorId')->references('Id')->on('Instructor')->cascadeOnDelete();
        });

        Schema::create('InstructorAvailability', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('InstructorId');
            $table->unsignedTinyInteger('DayOfWeek');
            $table->time('StartTime');
            $table->time('EndTime');
            $table->boolean('IsRecurring')->default(true);
            $table->date('SpecificDate');
            $table->boolean('IsActive')->default(true);
            $table->string('Notes', 255)->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->dateTime('UpdatedAt')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('InstructorId')->references('Id')->on('Instructor')->cascadeOnDelete();
        });

        Schema::table('Lesson', function (Blueprint $table) {
            $table->index('StartTime', 'idx_lesson_starttime');
            $table->index('ClientId', 'idx_lesson_client');
            $table->index('InstructorId', 'idx_lesson_instructor');
        });

        Schema::table('Invoice', function (Blueprint $table) {
            $table->index('ClientId', 'idx_invoice_client');
            $table->index('Status', 'idx_invoice_status');
        });

        Schema::table('Payment', function (Blueprint $table) {
            $table->index('InvoiceId', 'idx_payment_invoice');
        });

        Schema::table('Notification', function (Blueprint $table) {
            $table->index(['UserId', 'IsRead'], 'idx_notification_user');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('email', 'idx_user_email');
        });

        Schema::table('Contact', function (Blueprint $table) {
            $table->index('UserId', 'idx_contact_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('InstructorAvailability');
        Schema::dropIfExists('ExamRequest');
        Schema::dropIfExists('Notification');
        Schema::dropIfExists('Payment');
        Schema::dropIfExists('InvoiceLine');
        Schema::dropIfExists('Invoice');
        Schema::dropIfExists('Lesson');
        Schema::dropIfExists('ClientPackage');
        Schema::dropIfExists('DrivingPackage');
        Schema::dropIfExists('InstructorVehicle');
        Schema::dropIfExists('Vehicle');
        Schema::dropIfExists('Instructor');
        Schema::dropIfExists('Client');
        Schema::dropIfExists('Contact');
        Schema::dropIfExists('Address');
    }
};