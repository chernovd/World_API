using Newtonsoft.Json.Linq;
using System;
using System.Linq;
using System.Windows.Forms;
using System.Xml;
using System.Xml.Linq;
using System.Xml.Schema;
using Newtonsoft.Json.Schema;
using System.Collections;
using Newtonsoft.Json;
using System.IO;
using System.Net;

namespace City
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        // schema for xml file
        string xsd = string.Format("http://localhost/api/city/cities_schema.xsd");
        //string json_schema = string.Format("http://localhost/api/city/cities_schema.json");

        //fetching json data from API
        private JObject APIOutput(string city)
        {
            string url = string.Format("http://localhost/api/city/read.php/{0}", city);

            using (var webClient = new WebClient())
            {
                try
                {
                    webClient.Headers["Content-Type"] = "application/json";
                    var json = webClient.DownloadString(url);
                    return JObject.Parse(json);
                }
                catch (Exception e)
                {
                    return JObject.Parse(null);
                }
            }
        }

        //Function that validates XML
        public bool ValidateSchema(XmlDocument xmlPath, string xsdPath)
        {
            XmlDocument xml = xmlPath;
            //xml.Load(xmlPath);

            xml.Schemas.Add(null, xsdPath);

            try
            {
                xml.Validate(null);
            }
            catch (XmlSchemaValidationException)
            {
                return false;
            }
            return true;
        }

     
        //Function that validates JSON
        public bool ValidateJson(JObject json)
        {
            var dataObject = new { data = File.ReadAllText(@"C:\xampp\htdocs\API\city\cities_schema.json") };
            
            string dataObjectString = JsonConvert.SerializeObject(dataObject);
            string schemaJson = @"{
    'definitions': {},
    '$schema': 'http://json-schema.org/draft-07/schema#',
    '$id': 'http://example.com/cities.schema.json',
    'type': 'object',
    'title': 'Cities Info',
    'required': [
      'Cities'
    ],
    'properties': {
      'Cities': {
        '$id': '#/properties/Cities',
        'type': 'array',
        'title': 'Cities Schema',
        'items': {
          '$id': '#/properties/Cities/items',
          'type': 'object',
          'title': 'City Info',
          'required': [
            'City'
          ],
          'properties': {
            'City': {
              '$id': '#/properties/Cities/items/properties/City',
              'type': 'object',
              'title': 'City Object',
              'required': [
                'ID',
                'Name',
                'CountryCode',
                'Country',
                'Population',
                'District',
                'Language',
                'Lang_Percentage'
              ],
              'properties':  {
                'ID': {
                  '$id': '#/properties/City/properties/ID',
                  'type': 'integer',
                  'title': 'The unique ID of the city',
                  'default': '',
                  'examples': [1]
                },
                'Name': {
                  '$id': '#/properties/City/properties/Name',
                  'type': 'string',
                  'title': 'Name of the city',
                  'default': '',
                  'examples': ['Kabul']
                },
                'CountryCode': {
                  '$id': '#/properties/City/properties/CountryCode',
                  'type': 'string',
                  'title': 'Country Code',
                  'default': '',
				  'maxLength' : 3,
				  'minLength' : 3,
                  'examples': ['AFG']
                },
                'Country': {
                  '$id': '#/properties/City/properties/Country',
                  'type': 'string',
                  'title': 'Country name',
                  'default': '',
                  'examples': ['Afghanistan']
                },
                'Population': {
                  '$id': '#/properties/City/properties/Population',
                  'type': 'integer',
                  'title': 'Population amount',
                  'default': '',
				  'minimum': 0,
                  'examples': [1780000]
                },
                'District': {
                  '$id': '#/properties/City/properties/District',
                  'type': 'string',
                  'title': 'District name',
                  'default': '',
                  'examples': ['Kabol']
                },
                'Language': {
                  '$id': '#/properties/City/properties/Language',
                  'type': 'array',
                  'title': 'Language Schema',
                  'items': {
                    '$id': '#/properties/City/properties/Language/items',
                    'type': 'string',
                    'title': 'Languages',
                    'default': '',
                    'examples': ['Pashto', 'Dari', 'Uzbek', 'Turkmenian', 'Balochi']
                  }
                },
                'Lang_Percentage': {
                  '$id': '#/properties/City/properties/Lang_Percentage',
                  'type': 'array',
                  'title': 'Percentage Schema',
                  'items': {
                    '$id': '#/properties/City/properties/Lang_Percentage/items',
                    'type': 'string',
                    'title': 'Percentage of each language',
                    'default': '',
                    'examples': ['52.4', '32.1', '8.8', '1.9', '0.9']
                  }
                }
              }
            }
          }
        }
      }
    }
  }";
     
            JSchema schema = JSchema.Parse(dataObjectString);
            bool valid = json.IsValid(schema);
            return valid;

        }

        //returns XML data from API
        private XmlDocument XmlUri(string city)
        {
            string uri = string.Format("http://localhost/api/city/read.php/{0}", city);

            HttpWebRequest request = WebRequest.Create(uri) as HttpWebRequest;
            request.ContentType = "application/xml";
            HttpWebResponse response = request.GetResponse() as HttpWebResponse;

            XmlDocument xmlDoc = new XmlDocument();
            xmlDoc.Load(response.GetResponseStream());
            return xmlDoc;

        }

        // fetch all the data about specific city
        private void collectData(string city)
        {
            //XML
            if (Xml.Checked)
            {
          
                XmlDocument xmlDoc = XmlUri(city);
                var lang = new ArrayList();
                var perc = new ArrayList();
                XmlNodeList list = xmlDoc.SelectNodes("/Cities/City");
                foreach (XmlNode xn in list)
                {

                    string country_code = xn.SelectSingleNode("CountryCode").InnerText;  //Get attribute-id 
                    string district = xn.SelectSingleNode("District").InnerText;
                    string population = xn.SelectSingleNode("Population").InnerText;
                    string country = xn.SelectSingleNode("Country").InnerText;
                    string language = xn.SelectSingleNode("Languages").InnerText;
                    string percentage = xn.SelectSingleNode("Lang_Percentage").InnerText;
                    string[] lang_split = language.Split(',');
                    string[] perc_split = percentage.Split(',');
                    foreach (var item in lang_split)
                    {
                        lang.Add((string)(item));
                    }

                    foreach (var item2 in perc_split)
                    {
                        perc.Add((string)(item2));
                    }

                    //labels
                    label2.Text = city;
                    label3.Text = country_code;
                    label4.Text = district;
                    label5.Text = population;
                    label6.Text = country;
                    label12.Text = city;
                    label16.Text = (string)lang[0];


                }

                //Chart with population
                chart1.Series[0].LegendText = city;
                int population1 = cityToCompare(city);
                chart1.Series[0].Points.AddY(population1);

                ////Chart with languages
                for (int i = 0; i < lang.Count; i++)
                {
                    chart2.Series["s1"].Points.AddXY(lang[i].ToString(), perc[i].ToString());
                }
            }

            //JSON
            if(Json.Checked)
            {
                dynamic weatherInfoOutput = APIOutput(city);

                //fetch data
                string country = weatherInfoOutput.Cities[0].City.Country;
                string country_code = weatherInfoOutput.Cities[0].City.CountryCode;
                string population = weatherInfoOutput.Cities[0].City.Population;
                JArray language = weatherInfoOutput.Cities[0].City.Language;
                JArray percentage = weatherInfoOutput.Cities[0].City.Lang_Percentage;
                string district = weatherInfoOutput.Cities[0].City.District;

                //labels
                label2.Text = city;
                label3.Text = country_code;
                label4.Text = district;
                label5.Text = population;
                label6.Text = country;
                label12.Text = city;
                label16.Text = (string)(language[0]);

                //Chart with population
                chart1.Series[0].LegendText = city;
                int population1 = cityToCompare(city);
                chart1.Series[0].Points.AddY(population1);

                //Chart with languages
                for (int i=0; i<language.Count();i++)
                {
                    chart2.Series["s1"].Points.AddXY((string)(language[i]), (string)(percentage[i]));
                }  
            }
        }

        //returns population about chosen city 
        private int cityToCompare(string city)
        {
            int results = 0;

            //XML
            if (Xml.Checked)
            {
                XmlDocument xmlDoc = XmlUri(city);
                XmlNodeList list = xmlDoc.SelectNodes("/Cities/City");
                foreach (XmlNode xn in list)
                {
                    string population = xn.SelectSingleNode("Population").InnerText;
                    results = Int32.Parse(population);
                }
            }

            //JSON
            else if(Json.Checked)
            {
                dynamic weatherInfoOutput = APIOutput(city);
                int population = weatherInfoOutput.Cities[0].City.Population;
                results = population;   
            }

            return results;
        }

        private void Button1_Click(object sender, EventArgs e)
        {
            //XML
            if (Xml.Checked)
            {
                //Clear chart's points
                chart2.Titles.Clear();
                foreach (var series in chart1.Series)
                {
                    series.Points.Clear();
                }
                foreach (var series in chart2.Series)
                {
                    series.Points.Clear();

                }
   
                string city = textBox1.Text;

                //Validate XML and get all necessary information
                if (ValidateSchema(XmlUri(city), xsd))
                {
                    chart2.Titles.Add("Percentage of languages in");
                    label17.Text = "Validated!";
                    collectData(city);
                    label20.Text = city;
                    chart1.Series[0].LegendText = city;

                }
                else
                {
                    label17.Text = "Error!";
                }
            }

            //JSON
            if(Json.Checked)
            {
                //Clear chart's points
                chart2.Titles.Clear();
                foreach (var series in chart1.Series)
                {
                    series.Points.Clear();
                }
                foreach (var series in chart2.Series)
                {
                    series.Points.Clear();
        
                }

                 string city = textBox1.Text;
                 dynamic json = APIOutput(city);

                //Validate JSON and get all necessary information
                    if (ValidateJson(json))
                    {
                        chart2.Titles.Add("Percentage of languages in");
                        label17.Text = "Validated!";
                        collectData(city);
                        label20.Text = city;
                        chart1.Series[0].LegendText = city;

                    }
                    else
                    {
                        label17.Text = "Error!";
                    }

        }
         
        }

        //Compare population
        private void Button2_Click(object sender, EventArgs e)
        {
            string city = label12.Text;
            chart1.Series[0].LegendText = city;
            chart1.Series[1].Points.Clear();

            //Second city
            string city2 = textBox2.Text;
            int population2 = cityToCompare(city2);
            chart1.Series[1].LegendText = city2;
            chart1.Series[1].Points.AddY(population2);
        }

        private void Form1_Load(object sender, EventArgs e)
        {

        }
    }
}
