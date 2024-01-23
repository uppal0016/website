<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Quotes;

class QuotesTableAnniversarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $anniversaryQuotes = [
            "Cheers to an year of hard work, dedication, and growth! Happy work anniversary!",
            "An year of hard work, dedication, and growth! Happy work anniversary to a truly remarkable colleague. Your commitment and contributions are valued more than words can express. Here's to many more years of success together!",
            "Cheers to an year of hard work, dedication, and growth! Happy work anniversary and thank you for being an invaluable part of our team.",
            "Happy work anniversary to a truly exceptional colleague. Your commitment and contributions continue to inspire us all.",
            "An year of dedication, growth, and success! Happy work anniversary! Your commitment and hard work continue to inspire us all. Here's to many more years of achieving great milestones together.",
            "An year of dedication, growth, and achievements. Happy Work Anniversary! Your contributions make a difference every day, and we're grateful to have you on our team.",
            "Your unwavering commitment and hard work are truly commendable. Wishing you continued achievements and an even brighter future ahead. Congratulations on this milestone!",
            "We just wanted to thank you for everything you’ve done so far for our organization and the team. All though it's too little compared to all the efforts you have put throughout the years. We want to extend our heartiest wishes on your work anniversary.",
            "Hard work, Loyalty, and Diligence make the best employees. And we're glad to have you with all of these qualities. It’s your work anniversary today, and we couldn’t have thought of any better moment than today to appreciate you and wish you good luck in all your future endeavors.",
            "You have been an essential part of our organization's journey and success. We are eternally grateful for the dedication and passion you have shown. Thank you for being with us. Happy Work Anniversary!",
            "You have set an exemplary standard for all of us with your work ethics and your dedication. Thank you for everything you have done for us all these years. We wish you a Happy Work Anniversary!",
            "Having an employee like you is a matter of utmost pride and gratitude. Your loyalty and hard work have set an example for everyone in our organization. Thank you for being a part of our organization. Happy Work Anniversary!",
            "Hard work and loyalty is a gift not everyone possesses. But you got it, and we are glad to have someone like you working for us. Keep up your excellent work and continue to inspire us—cheers to all your incredible years of work. Happy Work Anniversary!",
            "Your loyalty and hard work can’t be remunerated but can be appreciated and motivated. It’s your work anniversary today. We wanted to thank you for your hard work and the loyalty you have shown towards our organization. Kudos!",
            "Everyone requires a person with an abundance of positive vibe and confidence to get things done perfectly. Thank you for being that person. Warm wishes on your work anniversary!",
            "Your positive attitude towards work inspires everyone here to give their best. It’s your work anniversary today, and we are thankful for having you with us. Happy Work Anniversary!",
            "Every organization should have an employee like you. You’re unique with your own set of skills and your positive attitude towards your work. And at this moment, We would like to extend my warm greetings on your Work Anniversary today.",
            "Your work ethics are commendable, and so are you as an inspiring individual. We are glad to have you amongst us. Kudos to your incredible years of work!",
            "A committed employee and an extraordinary human being. That’s what you are, and we are glad to have someone like you in our workforce. We wish you a happy work anniversary and"
        ];        

        foreach ($anniversaryQuotes as $quote) {
            Quotes::create([
                'quote' => $quote,
                'type' => 2,
                'status' => 1
            ]);
        }
    }
}
