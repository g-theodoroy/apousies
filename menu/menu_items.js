/* Tigra Menu items structure */
/* var MENU_ITEMS = [
	['Μενου', 'index.php',null,
		['ΥποΜενου1', '#'],
		['ΥποΜενου2', '#'],
		['ΥποΜενου3', '#', null,
			['ΥποΜενου3-1', '#'],
			['ΥποΜενου3-2',  null, null,
				['ΥποΜενου3-2-1', '#'],
				['ΥποΜενου3-2-2', '#'],
				['ΥποΜενου3-2-3', '#'],
			],
			['ΥποΜενου3-3', '#'],
			['ΥποΜενου3-4', '#'],
		],
		['ΥποΜενου4', '#']
	],
	['Μενου', '#'],
	['Μενου', '#']
];
*/

var MENU_ITEMS_PARENT = [
  ['Αρχική', 'index.php', null,
		['Περισσότερα...', 'tour.php'],
		['Οδηγίες Χρήσης', 'useful/odigies_apousies.pdf'],
//		['Εγκατάσταση', 'install_guide.php'],
		['Αρχικές Ρυθμίσεις', 'configure.php'],
		['Χρήστες', 'subusers.php'],
		['Δοκιμή (demo)', 'demologin.php']
  ],
  ['xls από myschool', null, null,
        ['Μαθητές', 'importmyschstudents.php'],
        ['Απουσίες', 'importmyschapousies.php'],
        ['Προϋπάρχουσες', 'importmyschapousies_pre.php']
  ],
  ['Τμήματα', null, null,
		['Επιλογή', 'class.php'],
		['Ρυθμίσεις', 'parameters.php']
  ],
  ['Μαθητές', null, null,
		['Εισαγωγή', 'newstudent.php'],
		['Κατάλογος', 'students.php'],
		['Επεξεργασία', 'editstudent.php'],
		['Όλοι οι μαθητές', 'allstudents.php'],
		['Εξαγωγή XLS', 'exportstudentsxls.php'],
		['Εισαγωγή XLS', 'importstudentsxls.php']
  ],
  ['Απουσίες', null, null,
		['Καταχώρηση', 'apousies.php'],
    ['Δικαιολόγηση', 'dikaiologisi.php', null,
			['Ιστορικό', 'dikhistory.php']
    ],
        ['Προϋπάρχουσες', 'apousies_pre.php',],
		['xls για myschool', 'apousies4myschxls.php']
  ],
  ['Παρουσιολόγιο', null, null,
        ['Γυμνάσιο-Α΄Λυκείου', 'parousiologio.php?t=a'],
		['Λυκείου', 'parousiologio.php?t=l'],
        ['Τρίμηνα', 'parousiologio.php?t=g']
  ],
	['Αθροίσματα', 'prints_sel.php'],
	['Στατιστικα', 'statistics.php'],
  ['Ειδοποιητήρια', null, null,
		['με Email', 'emailedit.php'],
		['με SMS', 'smsedit.php'],
		['Εκτύπωση', 'paperedit.php'],
    ['Ιστορικό', null, null,
			['Επεξεργασία', 'paperhistory.php'],
			['Εκτύπωση', 'paperhistoryprint.php', {'tw': '_blank' }]
    ],
		['Ρυθμίσεις', 'parameters.php']
  ],
  ['Ετικέτες', 'labelsetup.php', null
  ],
  ['Δεδομένα', null, null,
		['Εξαγωγή', 'exportdata.php'],
		['Εισαγωγή', 'importdata.php']
  ],
  ['Επικοινωνία', 'mailto:g.theodoroygmail.com?subject=Διαχείριση Απουσιών', null
  ]
]

var MENU_ITEMS_SUB = [
              	['Αρχική', 'index.php', null,
              		['Περισσότερα...', 'tour.php'],
              		['Οδηγίες Χρήσης', 'useful/odigies_apousies.pdf']
//              		['Εγκατάσταση', 'install_guide.php'],
//              		['Αρχικές Ρυθμίσεις', 'configure.php'],
//              		['Δοκιμή (demo)', 'demologin.php'],
              	],
              	['xls από myschool', null, null,
//              		['Μαθητές', 'importmyschstudents.php'],
              		['Απουσίες', 'importmyschapousies.php'],
              		['Προϋπάρχουσες', 'importmyschapousies_pre.php']
              	],
              	['Τμήματα', null, null,
              		['Επιλογή', 'class.php'],
              		['Ρυθμίσεις', 'parameters.php']
              	],
              	['Μαθητές', null, null,
//              		['Εισαγωγή', 'newstudent.php'],
              		['Κατάλογος', 'students.php'],
//              		['Επεξεργασία', 'editstudent.php'],
//              		['Όλοι οι μαθητές', 'allstudents.php'],
              		['Εξαγωγή XLS', 'exportstudentsxls.php']
//              		['Εισαγωγή XLS', 'importstudentsxls.php'],
              	],
              	['Απουσίες', null, null,
              		['Καταχώρηση', 'apousies.php'],
              		['Δικαιολόγηση', 'dikaiologisi.php', null,
              			['Ιστορικό', 'dikhistory.php']
                ],
                    ['Προϋπάρχουσες', 'apousies_pre.php',],
                    ['xls για myschool', 'apousies4myschxls.php']
              	],
              	['Παρουσιολόγιο', null, null,
                    ['Γυμνάσιο-Α΄Λυκείου', 'parousiologio.php?t=a'],
                    ['Λυκείου', 'parousiologio.php?t=l'],
              		['Τρίμηνα', 'parousiologio.php?t=g']
              	],
              	['Αθροίσματα', 'prints_sel.php'],
              	['Στατιστικα', 'statistics.php'],
              	['Ειδοποιητήρια', null, null,
                    ['με Email', 'emailedit.php'],
                    ['με SMS', 'smsedit.php'],
                    ['Εκτύπωση', 'paperedit.php'],
              		['Ιστορικό', null, null,
              			['Επεξεργασία', 'paperhistory.php'],
              			['Εκτύπωση', 'paperhistoryprint.php', {'tw': '_blank' }]
              		],
              		['Ρυθμίσεις', 'parameters.php']
              	],
              	['Ετικέτες', 'labelsetup.php', null
              	],
              	['Δεδομένα', null, null,
              		['Εξαγωγή', 'exportdata.php']
//              	['Εισαγωγή', 'importdata.php'],
              	],
              	['Επικοινωνία', 'mailto:g.theodoroygmail.com?subject=Διαχείριση Απουσιών', null
              	]
]

