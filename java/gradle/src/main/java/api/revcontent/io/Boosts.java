package api.revcontent.io;

import api.revcontent.io.response.Boost;
import api.revcontent.io.response.Filter;
import api.revcontent.io.response.Response;
import com.fasterxml.jackson.databind.ObjectMapper;
import org.glassfish.jersey.client.ClientConfig;
import org.glassfish.jersey.jackson.JacksonFeature;

import javax.ws.rs.client.*;
import javax.ws.rs.core.MediaType;

/**
 * BOOSTS
 */
class Boosts {
    Client client;

    public Boosts() {
        ClientConfig clientConfig = new ClientConfig();
        clientConfig.register(JacksonFeature.class);
        client = ClientBuilder.newClient(clientConfig);
    }

    /**
     * Get Access information
     *
     * @return AccessInformation
     */
    AccessInformation getAccess(CredentialInfo credentialInfo) {
        AccessInformation accessInfo = new AccessInformation();
        String rawResponse = "";
        try {
            WebTarget webTarget = this.client
                    .target("https://api.revcontent.io/oauth/token");
            javax.ws.rs.core.Response response = webTarget
                    .request(MediaType.APPLICATION_JSON_TYPE)
                    .header("cache-control", "no-cache")
                    .post(Entity.entity(
                            "grant_type=client_credentials&client_id="
                                    + credentialInfo.clientID
                                    + "&client_secret="
                                    + credentialInfo.clientSecret,
                            MediaType.APPLICATION_FORM_URLENCODED)
                    );
            ObjectMapper mapper = new ObjectMapper();
            rawResponse = response.readEntity(String.class);
            accessInfo = mapper.readValue(rawResponse, AccessInformation.class);
        } catch (Throwable t) {
            System.out.println(t.getMessage());
        } finally {
            System.out.println("Raw response: " + rawResponse);
        }

        return accessInfo;
    }

    Boost[] getBoosts(AccessInformation accessInformation, Filter filter) {
        Boost[] boosts = {};
        String rawResponse = "";
        Response response;
        try {
            rawResponse = this.client
                .target("https://api.revcontent.io/stats/api/v1.0/boosts")
                .queryParam("limit", filter.limit)
                .queryParam("offset", filter.offset)
                .request(MediaType.APPLICATION_JSON_TYPE)
                .header("cache-control", "no-cache")
                .header("Authorization", accessInformation.token_type + " " + accessInformation.access_token)
                .get(String.class);

            ObjectMapper mapper = new ObjectMapper();
            response = mapper.readValue(rawResponse, Response.class);
            if (response.success) {
                boosts = response.getData();
            }
        } catch (Throwable t) {
            System.out.println(t.getMessage());
        } finally {
            System.out.println("Raw response: " + rawResponse);
        }

        return boosts;
    }
}

