<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('departments')->insert([
            ['name' => 'กลุ่มบริการทั่วไป', 'color_code' => '#78909C'],
            ['name' => 'กลุ่มพัฒนาองค์กร', 'color_code' => '#5C6BC0'],
            ['name' => 'กลุ่มยุทธศาสตร์และแผนงาน', 'color_code' => '#3F51B5'],
            ['name' => 'กลุ่มพัฒนานวัตกรรมและวิจัย', 'color_code' => '#00BCD4'],
            ['name' => 'กลุ่มสื่อสารความเสี่ยงโรคและภัยสุขภาพ', 'color_code' => '#039BE5'],
            ['name' => 'กลุ่มห้องปฏิบัติการทางการแพทย์ด้านควบคุมโรค', 'color_code' => '#009688'],
            ['name' => 'กลุ่มระบาดวิทยา และตอบโต้ภาวะฉุกเฉินทางสาธารณสุข', 'color_code' => '#D32F2F'],
            ['name' => 'กลุ่มโรคติดต่อ', 'color_code' => '#C2185B'],
            ['name' => 'กลุ่มโรคติดต่อเรื้อรัง', 'color_code' => '#E91E63'],
            ['name' => 'กลุ่มโรคไม่ติดต่อ', 'color_code' => '#4CAF50'],
            ['name' => 'กลุ่มโรคจากการประกอบอาชีพและสิ่งแวดล้อม', 'color_code' => '#8BC34A'],
            ['name' => 'กลุ่มโรคติดต่อนำโดยแมลง', 'color_code' => '#AFB42B'],
            ['name' => 'กลุ่มด่านควบคุมโรคติดต่อระหว่างประเทศ', 'color_code' => '#FF5722'],
            ['name' => 'ศูนย์บริการเวชศาสตร์ป้องกัน', 'color_code' => '#673AB7'],
            ['name' => 'ศูนย์ฝึกอบรมนักระบาดวิทยาภาคสนาม', 'color_code' => '#FF9800'],
            ['name' => 'งานเภสัชกรรม', 'color_code' => '#00E676'],
            ['name' => 'งานกฎหมาย', 'color_code' => '#455A64'],
            ['name' => 'งานธุรการ', 'color_code' => '#8D6E63'],
            ['name' => 'งานการเจ้าหน้าที่', 'color_code' => '#AB47BC'],
            ['name' => 'งานการเงินและบัญชี', 'color_code' => '#FBC02D'],
            ['name' => 'งานพัสดุ', 'color_code' => '#FF7043'],
            ['name' => 'งานยานพาหนะ', 'color_code' => '#546E7A'],
            ['name' => 'งานอาคารสถานที่', 'color_code' => '#795548'],
            ['name'=>'ศูนย์ควบคุมโรคติดต่อนำโดยแมลงที่ 9.1 จ.ชัยภูมิ','color_code'=>'#9575CD'],
            ['name'=>'ศูนย์ควบคุมโรคติดต่อนำโดยแมลงที่ 9.2 จ.บุรีรัมย์','color_code'=>'#F06292'],
            ['name'=>'ศูนย์ควบคุมโรคติดต่อนำโดยแมลงที่ 9.3 จ.สุรินทร์','color_code'=>'#4DB6AC'],
            ['name'=>'ศูนย์ควบคุมโรคติดต่อนำโดยแมลงที่ 9.4 อ.ปากช่อง','color_code'=>'#AED581'],
        ]);
    }
}