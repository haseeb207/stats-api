package api.revcontent.io;

import api.revcontent.io.response.Boost;
import api.revcontent.io.response.Filter;
import org.ini4j.Ini;
import org.junit.Before;
import org.junit.FixMethodOrder;
import org.junit.Test;
import org.junit.runners.MethodSorters;

import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;

import static org.junit.Assert.*;

@FixMethodOrder(MethodSorters.NAME_ASCENDING)
public class BoostsTest {
    private Boosts boosts;
    static AccessInformation expected;

    @Before
    public void setUp() {
        this.boosts = new Boosts();
    }

    @Test
    public void getAccess() {
        try {
            Ini ini = new Ini(new FileReader("config.ini"));
            Ini.Section credentials = ini.get("credentials");
            CredentialInfo credentialInfo = new CredentialInfo();
            credentialInfo.clientID = credentials.get("client_id");
            credentialInfo.clientSecret = credentials.get("client_secret");

            System.out.println("Client ID: " + credentialInfo.clientID);
            System.out.println("Client Secret: " + String.valueOf(credentialInfo.clientSecret));

            this.expected = this.boosts.getAccess(credentialInfo);

            System.out.println("Response token_type: " + this.expected.token_type);
            System.out.println("Response access_token: " + this.expected.access_token);

            assertEquals("Bearer", this.expected.token_type);
            assertSame(40, this.expected.access_token.length());
        } catch (IOException f) {
            System.out.println(f.getMessage());
        }
    }

    @Test
    public void getBoosts() {
        Filter filters = new Filter();
        filters.limit = 1;
        filters.offset = 0;
        Boost[] boosts = this.boosts.getBoosts(this.expected, filters);
        assertTrue(1 == boosts.length);
        System.out.println(boosts[0]);
        assertTrue(boosts[0].id.length() > 0);
    }
}
