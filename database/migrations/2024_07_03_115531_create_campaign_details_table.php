<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campaign_details', function (Blueprint $table) {
            $table->id();
            $table->integer('campaign_id');
            $table->integer('assigned_leader')->nullable();
            $table->integer('assigned_user')->nullable();
            $table->date('assigned_date')->nullable();
            $table->text('ref_no')->nullable();
            $table->text('progressStatus')->nullable();
            $table->text('progressSubStatus')->nullable();
            $table->text('campaignAgentRemark')->nullable();
            $table->text('currentstatusdate')->nullable();
            $table->text('applicanttypename')->nullable();
            $table->text('applicantidentity')->nullable();
            $table->text('applicantcompany')->nullable();
            $table->text('applicantbusinessregistrationnumber')->nullable();
            $table->text('applicantname')->nullable();
            $table->text('applicantgender')->nullable();
            $table->text('racename')->nullable();
            $table->text('applicantmobile')->nullable();
            $table->text('applicantfax')->nullable();
            $table->text('applicantotherphone')->nullable();
            $table->text('applicantaddress1')->nullable();
            $table->text('applicantaddress2')->nullable();
            $table->text('applicantaddress3')->nullable();
            $table->text('applicantpostcode')->nullable();
            $table->text('applicantcity')->nullable();
            $table->text('applicantstate')->nullable();
            $table->text('applicantemail')->nullable();
            $table->text('installationaddress1')->nullable();
            $table->text('installationaddress2')->nullable();
            $table->text('installationaddress3')->nullable();
            $table->text('installationpostcode')->nullable();
            $table->text('installationcity')->nullable();
            $table->text('installationstate')->nullable();
            $table->text('installationpropertytype')->nullable();
            $table->text('installationcontactperson')->nullable();
            $table->text('installationcontactnumber')->nullable();
            $table->text('billingaddress1')->nullable();
            $table->text('billingaddress2')->nullable();
            $table->text('billingaddress3')->nullable();
            $table->text('billingpostcode')->nullable();
            $table->text('billingcity')->nullable();
            $table->text('billingstate')->nullable();
            $table->text('productgroup')->nullable();
            $table->text('productname')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_details');
    }
};
