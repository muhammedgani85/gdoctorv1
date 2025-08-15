<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IllnessTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('illness_types')->insert([
            ['name' => 'Fever', 'description' => 'Elevated body temperature', 'category' => 'General'],
            ['name' => 'Headache', 'description' => 'Pain in head or neck region', 'category' => 'General'],
            ['name' => 'Cold', 'description' => 'Runny nose, sneezing', 'category' => 'General'],
            ['name' => 'Cough', 'description' => 'Persistent cough', 'category' => 'General'],
            ['name' => 'Body Ache', 'description' => 'Muscle pain or weakness', 'category' => 'General'],
            ['name' => 'Sore Throat', 'description' => 'Irritation or pain in throat', 'category' => 'General'],
            ['name' => 'Nausea', 'description' => 'Feeling of wanting to vomit', 'category' => 'General'],
            ['name' => 'Vomiting', 'description' => 'Expelling stomach contents', 'category' => 'General'],
            ['name' => 'Diarrhea', 'description' => 'Frequent loose or watery stools', 'category' => 'General'],
            ['name' => 'Constipation', 'description' => 'Difficulty in bowel movement', 'category' => 'General'],
            ['name' => 'Back Pain', 'description' => 'Discomfort in lower or upper back', 'category' => 'General'],
            ['name' => 'Stomach Pain', 'description' => 'Abdominal discomfort', 'category' => 'General'],
            ['name' => 'Allergies', 'description' => 'Reaction to allergens like dust or pollen', 'category' => 'General'],
            ['name' => 'Skin Rash', 'description' => 'Inflammation or discoloration on skin', 'category' => 'General'],
            ['name' => 'Eye Irritation', 'description' => 'Redness, itchiness or discomfort in eyes', 'category' => 'General'],
            ['name' => 'Ear Pain', 'description' => 'Pain inside or around the ear', 'category' => 'General'],
            ['name' => 'Toothache', 'description' => 'Pain in or around a tooth', 'category' => 'Dental'],
            ['name' => 'Bleeding Gums', 'description' => 'Gums bleeding during brushing or eating', 'category' => 'Dental'],
            ['name' => 'Cavities', 'description' => 'Holes in teeth caused by decay', 'category' => 'Dental'],
            ['name' => 'Sensitive Teeth', 'description' => 'Discomfort when consuming hot/cold foods', 'category' => 'Dental'],
            ['name' => 'Gingivitis', 'description' => 'Inflammation of gums', 'category' => 'Dental'],
            ['name' => 'Bad Breath', 'description' => 'Unpleasant odor from mouth', 'category' => 'Dental'],
            ['name' => 'Mouth Ulcers', 'description' => 'Painful sores inside the mouth', 'category' => 'Dental'],
            ['name' => 'Jaw Pain', 'description' => 'Pain in jaw area', 'category' => 'Dental'],
            ['name' => 'Swollen Gums', 'description' => 'Inflamed gum tissue', 'category' => 'Dental'],
            ['name' => 'Frequent Sneezing', 'description' => 'Repeated sneezing episodes', 'category' => 'General'],
            ['name' => 'Loss of Smell', 'description' => 'Inability to smell things', 'category' => 'General'],
            ['name' => 'Dizziness', 'description' => 'Feeling faint or unsteady', 'category' => 'General'],
            ['name' => 'Fatigue', 'description' => 'Extreme tiredness', 'category' => 'General'],
            ['name' => 'Shortness of Breath', 'description' => 'Breathlessness during activity', 'category' => 'General'],
            ['name' => 'Chest Pain', 'description' => 'Pain in chest area', 'category' => 'General'],
            ['name' => 'Palpitations', 'description' => 'Rapid or irregular heartbeat', 'category' => 'General'],
            ['name' => 'Burning Urination', 'description' => 'Burning sensation while urinating', 'category' => 'General'],
            ['name' => 'Frequent Urination', 'description' => 'Urinating more than usual', 'category' => 'General'],
            ['name' => 'Joint Pain', 'description' => 'Pain in joints', 'category' => 'General'],
            ['name' => 'Swelling', 'description' => 'Enlargement of body parts', 'category' => 'General'],
            ['name' => 'Dry Skin', 'description' => 'Flaky or itchy skin', 'category' => 'General'],
            ['name' => 'Hair Loss', 'description' => 'Excessive shedding of hair', 'category' => 'General'],
            ['name' => 'Acne', 'description' => 'Pimples and blackheads', 'category' => 'General'],
            ['name' => 'Cold Sores', 'description' => 'Blisters on lips or mouth', 'category' => 'General'],
            ['name' => 'Menstrual Cramps', 'description' => 'Period-related pain', 'category' => 'General'],
            ['name' => 'Irregular Periods', 'description' => 'Unusual menstrual cycle', 'category' => 'General'],
            ['name' => 'Anxiety', 'description' => 'Excessive worry or fear', 'category' => 'General'],
            ['name' => 'Depression', 'description' => 'Persistent sadness', 'category' => 'General'],
            ['name' => 'Insomnia', 'description' => 'Difficulty sleeping', 'category' => 'General'],
            ['name' => 'Snoring', 'description' => 'Loud breathing during sleep', 'category' => 'General'],
            ['name' => 'High Blood Pressure', 'description' => 'Hypertension', 'category' => 'General'],
            ['name' => 'Low Blood Pressure', 'description' => 'Hypotension', 'category' => 'General'],
            ['name' => 'High Blood Sugar', 'description' => 'Hyperglycemia', 'category' => 'General'],
            ['name' => 'Low Blood Sugar', 'description' => 'Hypoglycemia', 'category' => 'General'],
        ]);
    }
}
