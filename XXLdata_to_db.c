#include <stdio.h>
#include "libpq-fe.h"
#include <string.h>
#include <stdlib.h>

/*get line slot by separator*/
char *getfield(const char* line, int num) {
    const char *p = line;
    size_t len;
    char *res;
    for (;;) {
        len = strcspn(p, ";\n");
        if (--num <= 0)
            break;
        p += len;
        if (*p == ';')
            p++;
    }
    res = malloc(len + 1);
    if (res) {
        memcpy(res, p, len);
        res[len] = '\0';
    }
    return res;
}

int     main() {
        /*open the ginourmous file*/
        FILE *stream = fopen("exampleDataXXL1.csv", "r");
            if (stream == NULL) {
                fprintf(stderr, "Error opening input file!\n");
                exit(1);
            }
        /*prep DB variables   */ 
        PGconn          *conn;
        PGresult        *res;
        int             rec_count;
        int             row;
        int             col;


        /*connect to DB*/
        conn = PQconnectdb("dbname=postgres host=localhost user=postgres password=postgres");

        if (PQstatus(conn) == CONNECTION_BAD) {
                puts("We were unable to connect to the database");
                exit(0);
        }

        char line1[102400];
        int i = 0;
        fgets(line1, 102400, stream); /*trash the first line*/

        while (fgets(line1, 102400, stream)) {
                /*assign each slot to its own variable*/
                char *tmp1 = getfield(line1, 1);
                char *tmp2 = getfield(line1, 2);
                char *tmp3 = getfield(line1, 3);
                char *tmp4 = getfield(line1, 4);
                char *tmp5 = getfield(line1, 5);
                char *tmp6 = getfield(line1, 6);
                char *tmp7 = getfield(line1, 7);
                /*make pieses for SQL command*/
                char *part1 = "INSERT INTO dataxxl (code,dep,visit_time,first_name,last_name,email,isikukood) VALUES ('";
                char *part2 = "','";
                char *part3 = "');";
                /*assemble the SQL command to a single variable*/
                char inserting[102400];
                snprintf(inserting, sizeof inserting, "%s%s%s%s%s%s%s%s%s%s%s%s%s%s%s", part1, tmp1, part2,tmp2, part2,tmp3, part2,tmp4, part2,tmp5, part2,tmp6, part2,tmp7, part3);
                /*send the data to DB*/
                res = PQexec(conn, inserting);
                /*try not to get a memory leak*/
                free(tmp1);
                free(tmp2);
                free(tmp3);
                free(tmp4);
                free(tmp5);
                free(tmp6);
                free(tmp7);
                
                
                i++;

                 
        }
                
                /*for query files but leaving this here as a refrence*/
                if (PQresultStatus(res) != PGRES_TUPLES_OK) {
                puts("We did not get any data!");
                
                exit(0);
                }

                rec_count = PQntuples(res);

                printf("We received %d records.\n", rec_count);
                puts("==========================");

                for (row=0; row<rec_count; row++) {
                        for (col=0; col<3; col++) {
                                printf("%s\t", PQgetvalue(res, row, col));
                        }
                        puts("");
                }

                puts("==========================");
        
        
       
        
        /*close DB and file*/
        PQclear(res);

        PQfinish(conn);
        fclose(stream);
        return 0;
}
/*gcc -o thisfile thisfile.c -I/usr/include/postgresql -lpq -std=c99 */