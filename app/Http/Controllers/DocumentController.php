<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Document;
use App\DocumentPassword;
use App\DocumentRead;
use App\DocumentEmail;
use App\DocumentFavorite;
use App\DocumentRequest;
use App\Exports\DocumentRequestExport;
use App\User;
use File;
use Response;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Helper;
use DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $document = Document::orderBy('id', 'desc')->paginate(10);
        $password  = DocumentPassword::orderBy('id', 'desc')->get();
        $documentRead = DocumentRead::join('documents', 'documents.id', '=', 'document_read.document_id')->with('user')->paginate(10);
        $check = '';
        foreach ($password as $val) {
            if ($val->user_id == Auth::user()->id) {
                $check = 1;
            }
        }

        if ($request->ajax()) {
            $filters = [
                'start_date' => $request->query('start_date') ?? null,
                'end_date' => $request->query('end_date') ?? null,
            ];

            if ($document->isEmpty()) {
                $document = $this->getDocument($filters, 'admin');
                return view('document.pagination', ['document' => $document, 'check' => $check, 'documentRead' => $documentRead])->with('noRecord', true);
            } else {
                $document = $this->getDocument($filters, 'employee');
                return view('document.pagination', ['document' => $document, 'check' => $check, 'documentRead' => $documentRead])->with('noRecord', false);
            }
        } else {
            $filters = [
                'sort_by' => $request->query('sort_by') ?? null,
                'doc_name' => $request->query('search') ?? null ,
                'start_date' => $request->query('start_date') ?? null,
                'end_date' => $request->query('end_date') ?? null,
            ];

            if ($document->isEmpty()) {
                $document = $this->getDocument($filters, 'admin');
                return view('document.index', ['document' => $document, 'check' => $check, 'documentRead' => $documentRead])->with('noRecord', true);
            } else {
                $document = $this->getDocument($filters, 'employee');
                return view('document.index', ['document' => $document, 'check' => $check, 'documentRead' => $documentRead])->with('noRecord', false);
            }
        }
    }

    private function getDocument($filters = [], $user_type = 'employee')
    {
        $document = Document::query();
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $start_date = $filters['start_date'] . ' 00:00:00';
            $end_date = $filters['end_date'] . ' 23:59:59';
            $document->whereBetween('created_at', [$start_date, $end_date]);
        }

        if (isset($filters['doc_name'])) {
            $document->where('documents', 'like', '%' . $filters['doc_name'] . '%');
        }

        if(isset($filters['sort_by']) && $filters['sort_by'] != 'All') {
            $document->where('protected_file',$filters['sort_by']);
        }
        if ($user_type == 'admin') {
            $document = $document->latest()->paginate(10);
        } else {
            $document = $document->latest()->paginate(10);
        }
        return $document;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('document.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'document' => 'required',
            'protected_file' => 'required',
        ]);

        try {
            if ($request->hasFile('document')) {
                $documentFile = $request->file('document');
                $fileName = $documentFile->getClientOriginalName();
                $documentPath = public_path('images/document/' . $fileName);
                $pdfFileName = pathinfo($fileName, PATHINFO_FILENAME) . '.pdf';
                $existingPdfPath = public_path('images/document/' . $pdfFileName);
                if (file_exists($existingPdfPath)) {
                    return redirect()->back()->with("error", "Document with the same name already exists.");
                }
                $documentFile->move(public_path('images/document/'), $fileName);

                // Initialize PhpWord
                // $phpWord = new PhpWord();

                // Set PDF renderer (DomPDF)
                // Settings::setPdfRendererName('DomPDF');
                // Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));

                // Check the file extension
                $extension = $documentFile->getClientOriginalExtension();

                // if ($extension === 'pdf') {
                //     $pdfPath = $documentPath;
                // } elseif ($extension === 'docx') {
                //     $phpWord = IOFactory::load($documentPath);
                //     $pdfPath = public_path('images/document/' . pathinfo($fileName, PATHINFO_FILENAME) . '.pdf');
                //     $phpWord->save($pdfPath, 'PDF');
                // } elseif ($extension === 'doc') {
                //     exec("unoconv -f pdf '$documentPath' -o '$existingPdfPath'");
                //     $pdfPath = public_path('images/document/' . pathinfo($fileName, PATHINFO_FILENAME) . '.pdf');
                // } else {
                //     return redirect('/document')->with("error", "Invalid file format. Please upload a DOCX or PDF document.");
                // }

                if ($extension === 'pdf') {
                    $pdfPath = $documentPath;
                } elseif ($extension === 'doc' || $extension === 'docx') {
                    $command = "soffice --convert-to pdf --outdir " . escapeshellarg(public_path('images/document/')) . " " . escapeshellarg($documentPath);
                    shell_exec($command);
                    $pdfPath = public_path('images/document/' . $pdfFileName);
                } else {
                    return redirect('/document')->with("error", "Invalid file format. Please upload a DOC, DOCX or PDF document.");
                }

                // Get the file name from the path
                $fileNames = basename($pdfPath);

                // Save the file path to the database
                $document = new Document();
                $document->documents = $fileNames;
                $document->protected_file = $request->input('protected_file');
                $document->save();
                return redirect('/document')->with("flash_message", "Document uploaded Successfully");
            }
        } catch (\Exception $e) {
            return redirect('document')->with("error", $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $document = Document::find($id);
        return view('document.edit', ['document' => $document]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $document = Document::find($id);
            if (!$document) {
                return redirect('/document')->with("error", "Document not found.");
            }

            if ($request->hasFile('edit_document')) {
                $documentFile = $request->file('edit_document');
                $fileName = $documentFile->getClientOriginalName();
                $documentPath = public_path('images/document/' . $fileName);
                
                $baseFileName = pathinfo($fileName, PATHINFO_FILENAME);

                $pdfFileName = $baseFileName . '.pdf';
                $pdfPath = public_path('images/document/' . $pdfFileName);
                
                $docxFileName = $baseFileName . '.docx';
                $docxPath = public_path('images/document/' . $docxFileName);
                
                $docFileName = $baseFileName . '.doc';
                $docPath = public_path('images/document/' . $docFileName);

                if (file_exists($pdfPath) || file_exists($docxPath) || file_exists($docPath)) {
                    return redirect()->back()->with("error", "Document with the same name already exists.");
                }

                $documentFile->move(public_path('images/document/'), $fileName);

                // Delete the current file from the images/document/ folder
                if ($document->documents && file_exists(public_path('images/document/' . $document->documents))) {
                    if ($document->documents) {
                        $baseFileName = pathinfo($document->documents, PATHINFO_FILENAME);
                    
                        $extensions = ['pdf', 'docx', 'doc'];
                    
                        foreach ($extensions as $extension) {
                            $filePath = public_path('images/document/' . $baseFileName . '.' . $extension);
                            
                            if (file_exists($filePath)) {
                                unlink($filePath);
                            }
                        }
                    }
                }

                // Initialize PhpWord
                // $phpWord = new PhpWord();

                // Set PDF renderer (DomPDF)
                // Settings::setPdfRendererName('DomPDF');
                // Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));

                // Check the file extension
                $extension = $documentFile->getClientOriginalExtension();

                // if ($extension === 'pdf') {
                //     $pdfPath = $documentPath;
                // } elseif ($extension === 'docx') {
                //     $phpWord = IOFactory::load($documentPath);
                //     $pdfPath = public_path('images/document/' . pathinfo($fileName, PATHINFO_FILENAME) . '.pdf');
                //     $phpWord->save($pdfPath, 'PDF');
                // } elseif ($extension === 'doc') {
                //     exec("unoconv -f pdf '$documentPath' -o '$docPath'");
                //     $pdfPath = public_path('images/document/' . pathinfo($fileName, PATHINFO_FILENAME) . '.pdf');
                // } else {
                //     return redirect('/document')->with("error", "Invalid file format. Please upload a DOCX or PDF document.");
                // }
                if ($extension === 'pdf') {
                    $pdfPath = $documentPath;
                } elseif ($extension === 'doc' || $extension === 'docx') {
                    $command = "soffice --convert-to pdf --outdir " . escapeshellarg(public_path('images/document/')) . " " . escapeshellarg($documentPath);
                    shell_exec($command);
                    $pdfPath = public_path('images/document/' . $pdfFileName);
                } else {
                    return redirect('/document')->with("error", "Invalid file format. Please upload a DOC, DOCX or PDF document.");
                }

                // Update the document record
                $document->documents = basename($pdfPath);
                $document->protected_file = $request->input('protected_file');
                $document->save();
            } else {

                $document->protected_file = $request->input('protected_file');
                $document->save();
            }

            return redirect('/document')->with("flash_message", "Document updated Successfully");
        } catch (\Exception $e) {
            return redirect('/document')->with("error", $e->getMessage());
        }
    }

    public function display_pdf(Request $request, $id)
    {
        $document = new document;
        $document =   $document->where('id', $id)->first();
        $documentRead = DocumentRead::where(['document_id' => $document->id, 'user_id' => Auth::user()->id])->first();
        $page_no = $request->query('page_no');
        // $seconds = $document->time; 
        // $minutes = Carbon::createFromTimestamp($seconds)->format('i');
        $path = public_path('images/document/' . $document->documents);
        $pdf = file_get_contents($path);
        $number = preg_match_all("/\/Page\W/", $pdf, $dummy);
        $time = isset($documentRead) ? $documentRead->time : '';
        $page = isset($documentRead) ? $documentRead->pages : '';
        $user_id = Auth::user()->role_id;
        $favoriteDocument = DocumentFavorite::where('document_id', $document->id)->first();
        if ($favoriteDocument) {
            $document->favorite_document = "Yes";
        } else {
            $document->favorite_document = "No";
        }

        // if ($document->protected_file == 'Single') {
        //     $documentPassword = $this->getDocumentPassword($document->id);
        //     if ($documentPassword) {
        //         return view('document.show', ['pdfPath' => $document->documents, 'doc_id' => $id, 'time' => $time, 'page' => $page, 'totalpage' => $number ,'page_no' => $page_no]);
        //     } else {
        //         $this->request_password_genrate($document->id);
        //         return back()->with("flash_message", "Request Sent Successfully");
        //     }
        // } else {
        // }
        return view('document.show', ['pdfPath' => $document->documents, 'doc_id' => $id, 'time' => $time, 'page' => $page, 'totalpage' => $number, 'page_no' => $page_no, 'user_id' => $user_id, 'document' => $document]);
    }

    private function getDocumentPassword($document_id)
    {
        return DocumentPassword::where('document_id', $document_id)->first();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function request_password_genrate($document_id)
    {
        $email = "hr@talentelgia.in";

        $activationCode = Str::random(10); // Generate a random activation code
        $link = env('APP_URL') . '/' . "document/password/" . Auth::user()->id . '/' . $document_id.'/' . $activationCode;     
        Mail::send('mails.document_password_request', ['link' => $link], function ($message) use ($email) {
            $message->to($email);
            $message->subject('Request password generate');
        });
     
        if (count(Mail::failures()) > 0) {
            // Email sending failed
        } else {
            DocumentEmail::create([
                'document_id' => $document_id,
                'status' => 'sent',
                'user_id' => Auth::user()->id,
                'activation_code' => $activationCode,
            ]);
        }
     


        return back()->with("flash_message", "Email request sent successfully.");
    }

    public function document_password(Request $request, $id, $documentPassword)
    {
        if (Auth::user()->role_id == 5) {
            $user = User::where('id', $id)->first();

            $id = request()->segment(2);
            $activationCode = request()->segment(5);
            $document_email = DocumentEmail::where('activation_code', $activationCode)->first();
            if($document_email->status !== 'expired'){
                $documentPassword = $documentPassword;
                $document_email->update(['status' => 'expired']);
                $document_email->save();
                return view('document.create_document_password', ['user' => $user, 'documentPassword' => $documentPassword , 'activation_code' => $activationCode]);
            }else {
                return view('document.emailexpired');
            }
        

        } else {
            return redirect('/dashboard');
        }
    }

    public function password_history(Request $request)
    {


        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $query = DocumentPassword::orderBy('id', 'desc');

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }

        $document_password = $query->get();

        $userCounts = $document_password->groupBy('user_id')->map->count();

        $unique_documents = $document_password->unique('user_id')->paginate(10);
        foreach ($unique_documents as $document) {
            $user = User::where('id', $document->user_id)->first();
            $document->user_name = $user->first_name . ' ' . $user->last_name;
            $document->emp_code = $user->employee_code;

            $count = $userCounts->get($document->user_id, 0);
            $document->password_count = $count;
        }

        return view('document.password_history', ['document_password' => $unique_documents]);
    }

    public function document_password_details(Request $request, $document_id, $user_id)
    {

        $document_id = $request->route('document_id');
        $user_id = $request->route('user_id');


        $document_data = DocumentPassword::where('document_id', $document_id)->where('user_id', $user_id)->get();

        $document_data->transform(function ($document) {
            $document->password = substr($document->password, 0, 6) . str_repeat('*', strlen($document->password) - 6);
            $document->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $document->created_at)->format('d-m-y');
            return $document;
        });

        $response = [
            'success' => true,
            'data' => $document_data,
        ];

        return response()->json($response);
    }

    public function password_details($user_id)
    {
        $documents = DocumentPassword::where('user_id', $user_id)->get();
        $passwordCounts = $documents->groupBy('document_id')->map->count();
        $unique_documents = $documents->unique('document_id')->paginate(10);
        foreach ($unique_documents as $key => $document) {
            $doc = Document::where('id', $document->document_id)->first();
            $document->document_name = $doc->documents;
            $document->document_type = $doc->protected_file;

            $count = $passwordCounts->get($document->document_id, 0);
            $document->password_count = $count;
        }
        return view('document.password_details', ['documents' => $unique_documents]);
    }


    public function genrate_password(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|required',
        ]);
        $DocumentPassword = DocumentPassword::where('user_id', $request->user_id)->where('document_id', $request->document_id)->first();
        $activationCode = $request->activation_code;
        if ($DocumentPassword) {
            $document = Document::where('id', $DocumentPassword->document_id)->first();
            if ($document->protected_file == 'Multiple') {
                DocumentPassword::create(
                    [
                        'user_id' => $request->user_id,
                        'password' => Hash::make($request->password),
                        'document_id' => $request->document_id,
                    ]
                );
            } else {
                $DocumentPassword->update(
                    [
                        // 'user_id' => $request->user_id,
                        'password' => Hash::make($request->password),
                        // 'document_id' => $request->document_id,
                    ]
                );
            }
        } else {
            DocumentPassword::create(
                [
                    'user_id' => $request->user_id,
                    'password' => Hash::make($request->password),
                    'document_id' => $request->document_id,
                ]
            );
        }
        $document = new Document;
        $document_name= $document->where('id',$request->document_id)->value('documents');
        $users =  User::where('id', $request->user_id)->first();
        $email =  $users->email;
        $document_email = DocumentEmail::where('activation_code', $activationCode)->first();
        if($document_email->status !== 'expired'){
           $document_email->update(['status' => 'expired']);
           $document_email->save();
        }
        Mail::send('mails.document_password_response', ['password' =>  $request->password, 'document_id' => $document_name, 'emp_name' => $users->first_name], function ($message) use ($email) {
            $message->to($email);
            $message->subject(' Response password genrate');
        });
        return redirect()->to('/dashboard')->with("flash_message", "Document password created Succesfully");
    }
    public function time_pages(Request $request)
    {

        try {
            $document = new DocumentRead;
            $document = $document->where(['document_id' => $request->docid, 'user_id' => Auth::user()->id])->first();

            $og_document = Document::where('id', $request->docid)->first();

            if ($og_document->protected_file == "Multiple") {
                DocumentEmail::where(['document_id' => $request->docid, 'user_id' => Auth::user()->id])->delete();
            }

            if ($request->page > $request->pages) {
                $page = $request->page;
            } else {
                $page = $request->pages;
            }
            if ($document) {
                $pageTime = ($request->total_time - $document->time);
                if ($document->max_time < $pageTime) {
                    $maxtime = $pageTime;
                    $page1 =   $request->page;
                } else {
                    $maxtime = $document->max_time;
                    $page1 =   $document->page_no;
                }

                $document->update([
                    'user_id' => Auth::user()->id,
                    'time' =>  $request->total_time,
                    'pages' => $document->pages > $page ? $document->pages : $page,
                    'page_no' => $page1,
                    'max_time' => $maxtime,
                    'last_page' => isset($request->lastpage) ? $request->lastpage : $request->page
                ]);
            } else {
                DocumentRead::create([
                    'user_id' => Auth::user()->id,
                    'document_id' => $request->docid,
                    'time' =>  $request->total_time,
                    'pages' => $page,
                    'page_no' => $page,
                    'max_time' => $request->total_time,
                    'last_page' => isset($request->lastpage) ? $request->lastpage : $request->page
                ]);
            }
            return response()->json([
                'flash_message' => 'Added Successfully!!'
            ]);
        } catch (\Exception $e) {
            throw new Exception("Error Processing Request", $e->message());
        }
    }
    public function documentView(Request $request)
    {
        try {
            $document = Document::where('id', $request->document)->first();
            if ($document->protected_file == 'Multiple') {
                $user = DocumentPassword::where('document_id', $request->document)
                    ->where('user_id', Auth::user()->id)
                    ->latest()->first();
            } else {
                $user = DocumentPassword::where('document_id', $request->document)
                    ->where('user_id', Auth::user()->id)
                    ->first();
            }

            if (Hash::check($request->password, $user->password)) {
                session()->put('user_id', $user->user_id);
                $document = Document::where('id', '=', $user->document_id)->first();
                $user->update(['enable' => "Yes"]);
                if ($document->protected_file == "Multiple") {
                    return redirect()->route('display_pdf', ['id' => $document->id]);
                }

                $user = DocumentPassword::where('document_id', $request->document)->where('user_id', Auth::user()->id)->first();
                if ($user) {
                    DocumentRead::create([
                        'user_id' => Auth::user()->id,
                        'document_id' => $user->document_id,
                        'time' =>  0,
                        'pages' => 0,
                        'page_no' => 0,
                        'max_time' => 0,
                        'last_page' => 0
                    ]);
                }
                return back()->with("flash_message", "Your document is ready to view.");
            } else {
                return back()->with("error", "Password does not match!");
            }
        } catch (\Exception $e) {
            return back()->with("error", " Your password not genrated. Please Send Request!");
        }
    }
    public function documentDetails(Request $request)
    {
        if (Auth::user()->role_id !== 5) {

            $totalDocumentRead =  DocumentRead::get();
            $overallTime = [];
            foreach ($totalDocumentRead as $totaltime) {
                $overallTime[]  = $totaltime->time;
            }
            $overallTime  = array_sum($overallTime);
            $secs = $overallTime % 60;
            $hrs = $overallTime / 60;
            $mins = $hrs % 60;
            $hrs = $hrs / 60;
            $total_time =  (int)$hrs . ":" . (int)$mins . ":" . (int)$secs;

            $documentRead = DocumentRead::join('documents', 'document_read.document_id', '=', 'documents.id')->where('user_id', '=', Auth::user()->id)->with('user');

            $documentReadIds = DocumentRead::where('user_id', '=', Auth::user()->id)->pluck('document_id')->toArray();

            $user_id = Auth::user()->id;
            $favoriteDocuments = DocumentFavorite::where('user_id', $user_id)->join('documents', 'document_favorite_table.document_id', '=', 'documents.id')->select('documents.id', 'documents.documents', 'documents.protected_file', 'documents.created_at');

            $documents = Document::whereNotIn('id', $documentReadIds)
                ->orderBy('id', 'DESC');

                $search = $request->input('search');

                if ($search) {
                    $documentRead->where('documents', 'like', '%' . $search . '%');
                    $documents->where('documents', 'like', '%' . $search . '%');
                    $favoriteDocuments->where('documents', 'like', '%' . $search . '%');
                }
        
                $documentRead = $documentRead->paginate(10, ['*'], 'current');
                $documents = $documents->paginate(10, ['*'], 'new');
                $favoriteDocuments = $favoriteDocuments->paginate(10, ['*'], 'favoriteDocuments');

            foreach ($documents as $doc) {
                if ($doc->protected_file == "Single") {
                    $doc->is_password = DocumentPassword::where('document_id', $doc->id)->first() ? "Yes" : "No";
                    $doc->is_email = DocumentEmail::where('document_id', $doc->id)->where('status', '!=', 'expired')->first() ? "Yes" : "No";
                } elseif ($doc->protected_file == "Multiple") {
                    $latestRow = DocumentPassword::where('document_id', $doc->id)
                        ->where('user_id', Auth::user()->id)
                        ->latest()
                        ->first();
                    $doc->is_email = DocumentEmail::where(['document_id' => $doc->id, 'user_id' => Auth::user()->id])->where('status', '!=', 'expired')->first() ? "Yes" : "No";

                    if ($latestRow && $latestRow->enable === 'Yes') {
                        $doc->multiple_password = "yes";
                    } elseif ($latestRow && $latestRow->enable === 'No') {
                        $doc->multiple_password = "no";
                    } else {
                        $doc->multiple_password = null;
                    }

                    $doc->is_email = DocumentEmail::where(['document_id' => $doc->id, 'user_id' => Auth::user()->id])->first() ? "Yes" : "No";
                }
            }

            foreach ($documentRead as $document) 
            {
                if ($document->protected_file == "Single") {
                    $document->is_password = DocumentPassword::where('document_id', $document->id)->first() ? "Yes" : "No";
                    $document->is_email = DocumentEmail::where([
                        'document_id' => $document->id,
                        'user_id' => Auth::user()->id
                    ])->where('status', '!=', 'expired')->first() ? "Yes" : "No";
                   
                } elseif ($document->protected_file == "Multiple") {
               
                    $latestRow = DocumentPassword::where('document_id', $document->id)
                        ->where('user_id', Auth::user()->id)
                        ->latest()
                        ->first();
                    if ($latestRow && $latestRow->enable === 'Yes') {
                        $document->multiple_password = "yes";
                    } elseif ($latestRow && $latestRow->enable === 'No') {
                        $document->multiple_password = "no";
                    } else {
                        $document->multiple_password = null;
                    }
                    $document->is_email = DocumentEmail::where(['document_id' => $document->id, 'user_id' => Auth::user()->id])->where('status', '!=', 'expired')->first() ? "Yes" : "No";
                }
            }
            
            foreach ($favoriteDocuments as $favoriteDocument) 
            {
                if ($favoriteDocument->protected_file == "Single") {
                    $favoriteDocument->is_password = DocumentPassword::where('document_id', $favoriteDocument->id)->first() ? "Yes" : "No";
                    $favoriteDocument->is_email = DocumentEmail::where([
                        'document_id' => $favoriteDocument->id,
                        'user_id' => Auth::user()->id
                    ])->where('status', '!=', 'expired')->first() ? "Yes" : "No";
                   
                } elseif ($favoriteDocument->protected_file == "Multiple") {
                    $latestRow = DocumentPassword::where('document_id', $favoriteDocument->id)
                        ->where('user_id', Auth::user()->id)
                        ->latest()
                        ->first();
                    if ($latestRow && $latestRow->enable === 'Yes') {
                        $favoriteDocument->multiple_password = "yes";
                    } elseif ($latestRow && $latestRow->enable === 'No') {
                        $favoriteDocument->multiple_password = "no";
                    } else {
                        $favoriteDocument->multiple_password = null;
                    }
                    $favoriteDocument->is_email = DocumentEmail::where(['document_id' => $favoriteDocument->id, 'user_id' => Auth::user()->id])->where('status', '!=', 'expired')->first() ? "Yes" : "No";
                }
            }
            
            if ($request->ajax()) {
                if ($request->section == 'new_manage_document') {
                    return view('document.new_pagination', compact('total_time', 'documents'));
                } else if ($request->section == 'favorite_manage_document') {
                    return view('document.favorite_pagination', compact('favoriteDocuments'));
                } else {
                    return view('document.current_pagination', compact('total_time', 'documentRead'));
                }
            } else {
                return view('document.document_detail', compact('total_time', 'documentRead', 'documents', 'favoriteDocuments'));
            }
        } else {
            return redirect('/dashboard');
        }
    }
    public function destroy($id)
    {
        try {
            $document = Document::findOrFail($id);
            $documentId = $document->id;

            // Delete the related data
            DocumentPassword::where('document_id', $documentId)->delete();
            DocumentRead::where('document_id', $documentId)->delete();
            DocumentEmail::where('document_id', $documentId)->delete();
            DocumentRequest::where('document_id', $documentId)->delete();

            if ($document->documents && file_exists(public_path('images/document/' . $document->documents))) {
                $baseFileName = pathinfo($document->documents, PATHINFO_FILENAME);
    
                $extensions = ['pdf', 'docx', 'doc'];
    
                foreach ($extensions as $extension) {
                    $filePath = public_path('images/document/' . $baseFileName . '.' . $extension);
    
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            // Delete the document itself
            $document->delete();
            return redirect('/document')->with("flash_message", "Document deleted successfully.");
        } catch (\Exception $e) {
            return redirect('document')->with("error", $e);
        }
    }

    public function document_list(Request $request, $user_id)
    {
        $document = $request->route('id');
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        if ($start_date && $end_date) {
            $start_date = $start_date . ' 00:00:00';
            $end_date = $end_date . ' 23:59:59';
            $documentRead = DocumentRead::select('user_id', 'last_page', 'updated_at')->where('document_id', $document)->whereBetween('created_at', [$start_date, $end_date])->get()->paginate(10);
        } else {
            $documentRead = DocumentRead::select('user_id', 'last_page', 'updated_at')->where('document_id', $document)->get()->paginate(10);
        }

        $documentCount = $documentRead->count();

        if ($documentRead->isEmpty()) {
            return view('document.view_document', ['documentCount' => $documentCount, 'documentRead' => $documentRead])->with('noRecord', true);
        }
        return view('document.view_document', ['documentCount' => $documentCount, 'documentRead' => $documentRead])->with('noRecord', false);
    }

    public function document_management(Request $request)
    {

        $sort_by = $request->query('sort_by');
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
        $doc_name = $request->query('search');

        $documents = DocumentRead::query();

        if ($start_date && $end_date) {
            $start_date = $start_date . ' 00:00:00';
            $end_date = $end_date . ' 23:59:59';
            $documents = $documents->whereBetween('created_at', [$start_date, $end_date]);
        }

        if ($doc_name) {
            $documents = $documents->whereHas('document', function ($query) use ($doc_name) {
                $query->where('documents', 'like', '%' . $doc_name . '%');
            });
        }


        if ($sort_by && $sort_by == "old") {
            $documents = $documents->oldest()->get()->unique('document_id')->paginate(10);
        } else if ($sort_by && $sort_by == "new") {
            $documents = $documents->latest()->get()->unique('document_id')->paginate(10);
        } else {
            $documents = $documents->get()->unique('document_id')->paginate(10);
        }

        foreach ($documents as $document) {
            $documentId = $document->document_id;
            $documentName = Document::where('id', $documentId)->value('documents');
            $document->document_name = $documentName;
            $document->user_count = DocumentRead::where('document_id', $documentId)->count();
        }
        if ($request->ajax()) {
            return view('document.document_management_pagination', ['documents' => $documents]);
        } else {
            return view('document.document_management', ['documents' => $documents]);
        }
    }

    public function document_users_details(Request $request, $document_id)
    {
        $documents = DocumentRead::orderByDesc('time')->where('document_id', $document_id);

        $doc_name = $request->query('search');
        if ($doc_name) {
            $documents->whereHas('user', function ($query) use ($doc_name) {
                $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", '%' . $doc_name . "%");
            });
        }
        $documents = $documents->paginate(10);
        $matchedUserIds = [];

        foreach ($documents as $document) {
            $user = $document->user;

            if ($user) {
                $document->user_name = $user->first_name . ' ' . $user->last_name;
                $document->emp_code = $user->employee_code;
                $document->request_type = DocumentRequest::where('document_id', $document_id)->value('request_type');

                if (!in_array($user->id, $matchedUserIds)) {
                    $matchedUserIds[] = $user->id;
                }
            }
        }

        $uniqueUsers = User::whereIn('id', $matchedUserIds)->get();
        $documentCount = $documents->count();
        $documentName = Document::where('id', $document->document_id)->value('documents');
        if ($request->ajax()) {
            return view('document.document_users_details_pagination', ['documents' => $documents, 'uniqueUsers' => $uniqueUsers, 'documentCount' => $documentCount]);
        } else {
            return view('document.document_users_details', ['documents' => $documents, 'uniqueUsers' => $uniqueUsers, 'documentCount' => $documentCount, 'documentName' => $documentName]);
        }
    }

    public function addToFavorite(){
        $user_id = Auth::user()->id;
        $document_id = request('document_id');
        $existingFavorite = DocumentFavorite::where('user_id', $user_id)->where('document_id', $document_id)->first();
        if ($existingFavorite) {
            return response()->json(['error' => 'The document is already favorited!'], 409);
        }
        $documentFavorite = new DocumentFavorite;
        $documentFavorite->user_id = $user_id;
        $documentFavorite->document_id = $document_id;
        $documentFavorite->save();
    
        return response()->json(['message' => 'Document favorited successfully!'], 200);
    }

    public function requestDocument(Request $request)
    {
        try {
            $document_id = $request->input('document_id');
            $user_id = Auth::user()->id;
            $documentRequest = new DocumentRequest;
            $documentRequest->user_id = $user_id;
            $documentRequest->document_id = $document_id;
            if ($request->input('requestType') === 'hardCopy') {
                $documentRequest->request_type = 'Hard Copy';
            } else {
                $documentRequest->request_type = 'Soft Copy';
            }

            $email = "hr@talentelgia.in";
            $ccEmail = "info@talentelgia.in";
            $request_type = $documentRequest->request_type;
            $document_name = Document::where('id', $request->document_id)->value('documents');
            $documentRequest->save();

            Mail::send('mails.favorite_document_mail',['request_type' => $request_type,  'document_name' => $document_name ], function ($message) use ($email, $ccEmail) {
                $message->to($email);
                $message->cc($ccEmail);
                $message->subject('Document Request');
            });

            return response()->json(['message' => 'Request submitted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred. Please try again.']);
        }
    }

    public function removeFavorite($id){
        try {
            $documentFavorite = DocumentFavorite::where('document_id', $id)->where('user_id', Auth::user()->id)->first();
            $documentFavorite->delete();
            return redirect('/manage/document')->with("flash_message", "Favorite document removed successfully.");
        } catch (\Exception $e) {
            return redirect('/manage/document')->with("error", $e);
        }
    }

    public function generateDocumentPassword(Request $request, $id)
    {
        $length = 8;
        $uppercaseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercaseChars = 'abcdefghijklmnopqrstuvwxyz';
        $numberChars = '0123456789';
        $specialChars = '!@#$%^&*()_-+=<>?';
        
        $documentPassword = '';
        $documentPassword .= $uppercaseChars[rand(0, strlen($uppercaseChars) - 1)];
        $documentPassword .= $lowercaseChars[rand(0, strlen($lowercaseChars) - 1)];
        $documentPassword .= $numberChars[rand(0, strlen($numberChars) - 1)];
        $documentPassword .= $specialChars[rand(0, strlen($specialChars) - 1)];
        
        for ($i = 4; $i < $length; $i++) {
            $characters = $uppercaseChars . $lowercaseChars . $numberChars . $specialChars;
            $documentPassword .= $characters[rand(0, strlen($characters) - 1)];
        }
        $documentPassword = str_shuffle($documentPassword);

        $DocumentPassword = DocumentPassword::where('user_id', Auth::user()->id)->where('document_id', $id)->first();
        if ($DocumentPassword) {
            $document = Document::where('id', $DocumentPassword->document_id)->first();
            if ($document->protected_file == 'Multiple') {
                DocumentPassword::create(
                    [
                        'user_id' => Auth::user()->id,
                        'password' => Hash::make($documentPassword),
                        'document_id' => $id,
                    ]
                );
            } else {
                $DocumentPassword->update(
                    [
                        // 'user_id' => $request->user_id,
                        'password' => Hash::make($documentPassword),
                        // 'document_id' => $request->document_id,
                    ]
                );
            }
        } else {
            DocumentPassword::create(
                [
                    'user_id' => Auth::user()->id,
                    'password' => Hash::make($documentPassword),
                    'document_id' => $id,
                ]
            );
        }

        $document = new Document;
        $document_name= $document->where('id',$id)->value('documents');
        $users =  User::where('id', Auth::user()->id)->first();
        $email =  $users->email;
        Mail::send('mails.document_password_response', ['password' =>  $documentPassword, 'document_id' => $document_name, 'emp_name' => $users->first_name], function ($message) use ($email) {
            $message->to($email);
            $message->from('hr@talentelgia.in');
            $message->subject('Response password generate');
        });

        return back()->with("flash_message", "Please check your mail for password.");
    }

    public function request_documents()
    {
        $document_request = DocumentRequest::all();

        $document_request = $document_request->sortByDesc('created_at')->unique('document_id')->paginate(10);

        foreach ($document_request as $document) {
            $documentId = $document->document_id;
            $document->document_name = Document::where('id', $documentId)->value('documents');
            $document->user_count = DocumentRequest::where('document_id', $documentId)->distinct('user_id')->count();
            $document->request_count = DocumentRequest::where('document_id', $documentId)->count();
        }

        // if ($document_request->ajax()) {
        //     return view('document.document_request_pagination', ['document_request' => $document_request]);
        // } else {
        //     return view('document.document_request',  ['document_request' => $document_request]);
        // }
        return view('document.document_request',  ['document_request' => $document_request]);
    }

    public function document_employee_details()
    {        
        $document_data = DocumentRequest::select('users.employee_code', 'users.first_name', 'users.last_name')
        ->join('users', 'document_request.user_id', '=', 'users.id')
        ->distinct()
        ->get();
        
        $response = [
            'success' => true,
            'data' => $document_data,
        ];

        return response()->json($response);
    }
    
    public function document_request_document_details(Request $request, $document_id)
    {
        $document_id = $request->route('document_id');

        $document_data = DocumentRequest::join('users', 'document_request.user_id', '=', 'users.id')
            ->where('document_request.document_id', $document_id)
            ->select('document_request.*', 'users.employee_code', 'users.first_name', 'users.last_name')
            ->get();

        $response = [
            'success' => true,
            'data' => $document_data,
        ];

        return response()->json($response);
    }

    public function document_request_export()
    {
        $date = date('Y-m-d');
        $file_name = 'document_request_' . $date . '.xlsx';

        $document_datas = DocumentRequest::all();

        foreach($document_datas as $document_data){
            $document_data->first_name = User::where('id', $document_data->user_id)->value('first_name');
            $document_data->last_name = User::where('id', $document_data->user_id)->value('last_name');
            $document_data->employee_code = User::where('id', $document_data->user_id)->value('employee_code');
            $document_data->full_name = $document_data->first_name . ' ' . $document_data->last_name;
            $document_data->document_name = Document::where('id', $document_data->document_id)->value('documents');

            $document_request[] = [
                'Employee Name' => !empty($document_data->full_name) ? $document_data->full_name : '-',
                'Employee Code' => !empty($document_data->employee_code) ? $document_data->employee_code : '-',
                'Document Name' => !empty($document_data->document_name) ? $document_data->document_name : '-',
                'Requested Type' => !empty($document_data->request_type) ? $document_data->request_type : '-',
                'Requested Date' => !empty($document_data->created_at) ? $document_data->created_at->setTimezone('Asia/Kolkata')->format('d-m-Y') : '-',
            ];
        }
        if(isset($document_request)){
            return (new DocumentRequestExport($document_request))->download($file_name);
        } else {
            return redirect()->back()->with('error', 'Sorry, there is no data available for export.');
        }

    }
}
